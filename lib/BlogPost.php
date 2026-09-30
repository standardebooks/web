<?
use Safe\DateTimeImmutable;

use function Safe\copy;
use function Safe\exec;
use function Safe\getimagesize;
use function Safe\glob;
use function Safe\imagecopyresampled;
use function Safe\imagecreatetruecolor;
use function Safe\imageflip;
use function Safe\imagejpeg;
use function Safe\imagerotate;
use function Safe\mkdir;
use function Safe\preg_match;
use function Safe\preg_match_all;
use function Safe\preg_replace;
use function Safe\rmdir;
use function Safe\unlink;

/**
 * A blog post and its related content.
 */
class BlogPost{
	use Traits\PropertyFromRequest;

	public int $BlogPostId;
	public ?string $Description;
	public string $UrlTitle;
	public int $UserId;
	public DateTimeImmutable $CreatedAt;
	public DateTimeImmutable $UpdatedAt;
	public DateTimeImmutable $PublishedAt = NOW;
	public ?string $ImageCacheKey = null;
	public ?string $HeroImageCaption = null;

	public User $User{
		/** @throws Exceptions\UserNotFoundException If the author does not exist. */
		get => $this->User ??= User::Get($this->UserId);
	}

	public private(set) ?string $Excerpt = null{
		get{
			if(!isset($this->Excerpt)){
				if($this->Body !== null){
					$this->Excerpt = mb_substr(strip_tags($this->Body), 0, 200, 'utf-8') . '…';
				}
				elseif(isset($this->Subtitle)){
					$this->Excerpt = strip_tags($this->Subtitle);
				}
			}

			return $this->Excerpt;
		}
	}

	public string $Url{
		get => '/blog/' . $this->UrlTitle;
	}

	public string $EditUrl{
		get => $this->Url . '/edit';
	}

	/**
	 * Return the URL of the 1x JPEG hero image, if one exists.
	 */
	public ?string $HeroImageUrl{
		get => $this->ImageCacheKey !== null ? BLOG_POST_IMAGES_UPLOAD_PATH . '/' . $this->BlogPostId . '.jpg?v=' . $this->ImageCacheKey : null;
	}

	/**
	 * Return the URL of the 2x JPEG hero image, if one exists.
	 */
	public ?string $HeroImage2xUrl{
		get => $this->ImageCacheKey !== null ? BLOG_POST_IMAGES_UPLOAD_PATH . '/' .$this->BlogPostId . '@2x.jpg?v=' . $this->ImageCacheKey : null;
	}

	/**
	 * Return the URL of the 1x AVIF hero image, if one exists.
	 */
	public ?string $HeroImageAvifUrl{
		get => $this->ImageCacheKey !== null ? BLOG_POST_IMAGES_UPLOAD_PATH . '/' .$this->BlogPostId . '.avif?v=' . $this->ImageCacheKey : null;
	}

	/**
	 * Return the URL of the 2x AVIF hero image, if one exists.
	 */
	public ?string $HeroImageAvif2xUrl{
		get => $this->ImageCacheKey !== null ? BLOG_POST_IMAGES_UPLOAD_PATH . '/' .$this->BlogPostId . '@2x.avif?v=' . $this->ImageCacheKey : null;
	}

	/** A newline-separated list of related ebook identifiers. */
	public private(set) string $EbookIdentifiers{
		get{
			if(!isset($this->EbookIdentifiers)){
				$this->EbookIdentifiers = '';
				foreach($this->Ebooks as $ebook){
					$this->EbookIdentifiers .= $ebook->Identifier . "\n";
				}

				$this->EbookIdentifiers = trim($this->EbookIdentifiers);
			}

			return $this->EbookIdentifiers;
		}
	}

	/** @var array<Ebook> */
	public array $Ebooks{
		get{
			if(isset($this->BlogPostId)){
				$this->Ebooks ??= Db::Query('select Ebooks.* from Ebooks inner join BlogPostEbooks using (EbookId) where BlogPostId = ? order by BlogPostEbooks.SortOrder asc', [$this->BlogPostId], Ebook::class);
			}
			else{
				$this->Ebooks ??= [];
			}

			return $this->Ebooks;
		}
	}

	public HtmlFragment $Title{
		set(string|HtmlFragment $value) => new HtmlFragment($value);
	}

	public ?HtmlFragment $Subtitle{
		set(string|HtmlFragment|null $value) => $value !== null ? new HtmlFragment($value) : null;
	}

	/** May be `null` if the post redirects to a file, like the Public Domain Day posts. */
	public ?HtmlFragment $Body{
		set(string|HtmlFragment|null $value) => $value !== null ? new HtmlFragment($value) : null;
	}


	// *******
	// METHODS
	// *******

	/**
	 * @param ?string $userIdentifier
	 * @param ?string $ebookIdentifiers A newline-separated list of ebook identifiers to merge with any ebook identifiers found in the body.
	 * @param ?string $heroImagePath The path to the uploaded hero image in the system temporary directory.
	 * @param bool $hasHeroImage Whether this blog post should have a hero image.
	 *
	 * @throws Exceptions\BlogPostInvalidException
	 */
	public function Validate(?string $userIdentifier = null, ?string $ebookIdentifiers = null, ?string $heroImagePath = null, bool $hasHeroImage = false): void{
		$error = new Exceptions\BlogPostInvalidException();

		$this->Title ??= '';

		if($this->Title == ''){
			$error->Add(new Exceptions\BlogPostTitleRequiredException());
		}
		elseif(mb_strlen($this->Title, 'utf-8') > BLOG_POST_MAX_STRING_LENGTH){
			$error->Add(new Exceptions\StringTooLongException('Title'));
		}
		else{
			try{
				$this->Title->Validate();
			}
			catch(Exceptions\HtmlInvalidException $ex){
				$error->Add(new Exceptions\BlogPostTitleHtmlInvalidException($ex->RawMessage));
			}
		}

		$this->Subtitle ??= '';
		if($this->Subtitle == ''){
			$this->Subtitle = null;
		}
		else{
			try{
				$this->Subtitle->Validate();
			}
			catch(Exceptions\HtmlInvalidException $ex){
				$error->Add(new Exceptions\BlogPostSubtitleHtmlInvalidException($ex->RawMessage));
			}
		}

		$this->UrlTitle = Formatter::MakeUrlSafe(strip_tags($this->Title));

		$identifiers = explode("\n", $ebookIdentifiers ?? '');
		$this->Body ??= '';
		if($this->Body == ''){
			$this->Body = null;

			if(!file_exists(WEB_ROOT . '/blog-posts/' . $this->UrlTitle . '.php')){
				$error->Add(new Exceptions\BlogPostFileInvalidException());
			}
		}
		else{
			try{
				$this->Body->Validate();

				// Test the case where the fragment doesn't start with an element.
				if(!preg_match('/^</ius', $this->Body)){
					$error->Add(new Exceptions\HtmlInvalidException('Body must begin with an HTML element.'));
				}
			}
			catch(Exceptions\HtmlInvalidException $ex){
				$error->Add(new Exceptions\BlogPostBodyHtmlInvalidException($ex->RawMessage));
			}

			preg_match_all('/="((?:https:\/\/standardebooks.org)?\/ebooks\/[^\/"]+?\/[^"]+?)"/iu', $this->Body, $matches);

			foreach($matches[1] as $identifier){
				// Remove anchors or `/text/...` links
				$identifier = preg_replace('/(#.+|\/text|\/text\/.*)$/u', '', $identifier);

				// Add the full domain to URL if not present.
				$identifier = preg_replace('/^\//u', 'https://standardebooks.org/', $identifier);

				$identifiers[] = $identifier;
			}
		}

		foreach($this->Ebooks as $ebook){
			$identifiers[] = $ebook->Identifier;
		}

		$identifiers = array_unique($identifiers);

		$ebooks = [];
		foreach($identifiers as $identifier){
			if($identifier == ''){
				continue;
			}

			try{
				$ebooks[] = Ebook::GetByIdentifier($identifier);
			}
			catch(Exceptions\EbookNotFoundException){
				$error->Add(new Exceptions\EbookNotFoundException('Ebook not found: ' . $identifier));
			}
		}

		$this->Ebooks = $ebooks;

		$this->Description = trim($this->Description ?? '');
		if($this->Description == ''){
			$this->Description = null;
		}

		$this->HeroImageCaption = trim($this->HeroImageCaption ?? '');
		if($this->HeroImageCaption == '' || !$hasHeroImage){
			$this->HeroImageCaption = null;
		}
		elseif(mb_strlen($this->HeroImageCaption, 'utf-8') > BLOG_POST_MAX_STRING_LENGTH){
			$error->Add(new Exceptions\StringTooLongException('Hero image caption'));
		}

		if($userIdentifier !== null){
			try{
				$this->User = User::GetByIdentifier($userIdentifier);
				$this->UserId = $this->User->UserId;
			}
			catch(Exceptions\AmbiguousUserException | Exceptions\UserNotFoundException $ex){
				$error->Add($ex);
			}
		}
		else{
			if(!isset($this->UserId)){
				$error->Add(new Exceptions\BlogPostUserRequiredException());
			}
			else{
				try{
					User::Get($this->UserId);
				}
				catch(Exceptions\UserNotFoundException $ex){
					$error->Add($ex);
				}
			}
		}

		if($hasHeroImage && $this->ImageCacheKey === null && $heroImagePath === null){
			$error->Add(new Exceptions\ImageUploadInvalidException('A hero image is required.'));
		}
		elseif($heroImagePath !== null){
			try{
				$mimeType = Enums\ImageMimeType::FromFile($heroImagePath);
				if(!in_array($mimeType, [Enums\ImageMimeType::JPG, Enums\ImageMimeType::PNG, Enums\ImageMimeType::WEBP], true)){
					$error->Add(new Exceptions\ImageUploadInvalidException('Uploaded hero image must be a JPG, PNG, or WebP file.'));
				}

				$imageSize = getimagesize($heroImagePath);
				if($imageSize === null || $imageSize[0] == 0 || $imageSize[1] == 0){
					$error->Add(new Exceptions\ImageUploadInvalidException());
				}
			}
			catch(\Safe\Exceptions\ImageException){
				$error->Add(new Exceptions\ImageUploadInvalidException());
			}

			if(!is_writable(WEB_ROOT . '/images/blog-posts')){
				$error->Add(new Exceptions\ImageUploadInvalidException('Hero image path not writable.'));
			}
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	/**
	 * @throws Exceptions\BlogPostInvalidException
	 * @throws Exceptions\BlogPostExistsException
	 * @throws Exceptions\ImageUploadInvalidException If the hero image cannot be processed.
	 */
	public function Create(?string $userIdentifier = null, ?string $ebookIdentifiers = null, ?string $heroImagePath = null, bool $hasHeroImage = false): void{
		if($hasHeroImage && $heroImagePath === null && trim($this->HeroImageCaption ?? '') == ''){
			$hasHeroImage = false;
		}

		if(!$hasHeroImage){
			$heroImagePath = null;
			$this->HeroImageCaption = null;
		}

		$this->Validate($userIdentifier, $ebookIdentifiers, $heroImagePath, $hasHeroImage);
		$this->CreatedAt = NOW;
		if($heroImagePath !== null){
			$this->ImageCacheKey = $this->GenerateImageCacheKey();
		}

		Db::Query('start transaction');

		try{
			try{
				$this->BlogPostId = Db::QueryInt('
					insert into BlogPosts (UserId, Title, Subtitle, Description, UrlTitle, Body, ImageCacheKey, HeroImageCaption, PublishedAt, CreatedAt)
					values (?,
					        ?,
					        ?,
					        ?,
					        ?,
					        ?,
					        ?,
					        ?,
					        ?,
					        ?)
					returning BlogPostId
				', [$this->UserId, $this->Title, $this->Subtitle, $this->Description, $this->UrlTitle, $this->Body, $this->ImageCacheKey, $this->HeroImageCaption, $this->PublishedAt, $this->CreatedAt]);
			}
			catch(Exceptions\DuplicateDatabaseKeyException){
				throw new Exceptions\BlogPostExistsException();
			}

			$this->AddEbooks();

			if($heroImagePath !== null){
				$this->WriteHeroImage($heroImagePath);
			}

			Db::Query('commit');
		}
		catch(\Throwable $ex){
			try{
				Db::Query('rollback');
			}
			catch(\Throwable){
				// Preserve the original exception.
			}

			if(isset($this->BlogPostId)){
				$this->RemoveHeroImage();
			}

			throw $ex;
		}
	}

	/**
	 * @throws Exceptions\BlogPostInvalidException
	 * @throws Exceptions\BlogPostExistsException
	 * @throws Exceptions\ImageUploadInvalidException If the hero image cannot be processed.
	 */
	public function Save(?string $userIdentifier = null, ?string $ebookIdentifiers = null, ?string $heroImagePath = null, bool $hasHeroImage = false): void{
		if(!$hasHeroImage){
			$heroImagePath = null;
			$this->HeroImageCaption = null;
		}

		$this->Validate($userIdentifier, $ebookIdentifiers, $heroImagePath, $hasHeroImage);

		if(!$hasHeroImage){
			$this->ImageCacheKey = null;
		}
		elseif($heroImagePath !== null){
			$this->ImageCacheKey = $this->GenerateImageCacheKey();
		}

		Db::Query('start transaction');

		try{
			try{
				Db::Query('
					update BlogPosts
					set UserId = ?, Title = ?, Subtitle = ?, Description = ?, UrlTitle = ?, Body = ?, ImageCacheKey = ?, HeroImageCaption = ?, PublishedAt = ? where BlogPostId = ?
				', [$this->UserId, $this->Title, $this->Subtitle, $this->Description, $this->UrlTitle, $this->Body, $this->ImageCacheKey, $this->HeroImageCaption, $this->PublishedAt, $this->BlogPostId]);
			}
			catch(Exceptions\DuplicateDatabaseKeyException){
				throw new Exceptions\BlogPostExistsException();
			}

			Db::Query('delete from BlogPostEbooks where BlogPostId = ?', [$this->BlogPostId]);

			$this->AddEbooks();

			if(!$hasHeroImage){
				$this->RemoveHeroImage();
			}
			elseif($heroImagePath !== null){
				$this->WriteHeroImage($heroImagePath);
			}

			Db::Query('commit');
		}
		catch(\Throwable $ex){
			try{
				Db::Query('rollback');
			}
			catch(\Throwable){
				// Preserve the original exception.
			}

			throw $ex;
		}
	}

	/**
	 * Generate a random six-character cache key for a hero image.
	 */
	private function GenerateImageCacheKey(): string{
		return substr(hash('sha256', (string)rand()), 0, 6);
	}

	/**
	 * Generate the JPEG and AVIF hero images from an uploaded image.
	 *
	 * @param string $tempImagePath The path to the uploaded image in the system temporary directory.
	 *
	 * @throws Exceptions\ImageUploadInvalidException If the upload is not a supported image or conversion fails.
	 */
	private function WriteHeroImage(string $tempImagePath): void{
		try{
			$tempDirectory = UploadTempDirectory::GetPath() . '/' . uniqid('blog-hero-', true);
		}
		catch(Exceptions\TempDirectoryException){
			throw new Exceptions\ImageUploadInvalidException('Failed to process hero image.');
		}

		$filesWereInstalled = false;
		$destinationPaths = [];

		try{
			mkdir($tempDirectory);

			$mimeType = Enums\ImageMimeType::FromFile($tempImagePath);
			if(!in_array($mimeType, [Enums\ImageMimeType::JPG, Enums\ImageMimeType::PNG, Enums\ImageMimeType::WEBP], true)){
				throw new Exceptions\ImageUploadInvalidException('Uploaded hero image must be a JPG, PNG, or WebP file.');
			}

			$tempBasePath = $tempDirectory . '/' . $this->BlogPostId;
			$originalSuffix = '-original' . $mimeType->GetFileExtension();
			copy($tempImagePath, $tempBasePath . $originalSuffix);

			$sourceImage = match($mimeType){
				Enums\ImageMimeType::JPG => \Safe\imagecreatefromjpeg($tempImagePath),
				Enums\ImageMimeType::PNG => \Safe\imagecreatefrompng($tempImagePath),
				Enums\ImageMimeType::WEBP => \Safe\imagecreatefromwebp($tempImagePath),
			};

			$exifData = @exif_read_data($tempImagePath);
			$orientation = is_array($exifData) ? intval($exifData['Orientation'] ?? 1) : 1;

			// Apply the JPEG's EXIF orientation to its pixel data before cropping it.
			switch($orientation){
				case 2:
					imageflip($sourceImage, IMG_FLIP_HORIZONTAL);
					break;
				case 3:
					$sourceImage = imagerotate($sourceImage, 180, 0);
					break;
				case 4:
					imageflip($sourceImage, IMG_FLIP_VERTICAL);
					break;
				case 5:
					imageflip($sourceImage, IMG_FLIP_HORIZONTAL);
					$sourceImage = imagerotate($sourceImage, -90, 0);
					break;
				case 6:
					$sourceImage = imagerotate($sourceImage, -90, 0);
					break;
				case 7:
					imageflip($sourceImage, IMG_FLIP_HORIZONTAL);
					$sourceImage = imagerotate($sourceImage, 90, 0);
					break;
				case 8:
					$sourceImage = imagerotate($sourceImage, 90, 0);
					break;
			}

			foreach([[880, 250, '.jpg'], [1760, 500, '@2x.jpg']] as [$width, $height, $suffix]){
				$sourceWidth = imagesx($sourceImage);
				$sourceHeight = imagesy($sourceImage);
				$scale = max($width / $sourceWidth, $height / $sourceHeight);
				$scaledWidth = intval(ceil($sourceWidth * $scale));
				$scaledHeight = intval(ceil($sourceHeight * $scale));
				$destinationImage = imagecreatetruecolor($width, $height);

				imagecopyresampled($destinationImage, $sourceImage, intval(($width - $scaledWidth) / 2), intval(($height - $scaledHeight) / 2), 0, 0, $scaledWidth, $scaledHeight, $sourceWidth, $sourceHeight);
				imagejpeg($destinationImage, $tempBasePath . $suffix, 80);
			}

			foreach([['.jpg', '.avif'], ['@2x.jpg', '@2x.avif']] as [$sourceSuffix, $destinationSuffix]){
				exec(escapeshellarg(SITE_ROOT . '/web/scripts/cavif') . ' --quiet --quality 50 --speed 6 ' . escapeshellarg($tempBasePath . $sourceSuffix) . ' --output ' . escapeshellarg($tempBasePath . $destinationSuffix), $output, $resultCode);
				if($resultCode !== 0){
					throw new Exceptions\ImageUploadInvalidException('Failed to process hero image.');
				}
			}

			$destinationBasePath = WEB_ROOT . BLOG_POST_IMAGES_UPLOAD_PATH . '/' .$this->BlogPostId;
			$allSuffixes = ['.jpg', '@2x.jpg', '.avif', '@2x.avif', '-original.jpg', '-original.png', '-original.webp'];
			foreach($allSuffixes as $key => $suffix){
				$destinationPaths[$key] = $destinationBasePath . $suffix;
				if(is_file($destinationPaths[$key])){
					copy($destinationPaths[$key], $tempDirectory . '/backup-' . $key);
				}
			}

			$filesWereInstalled = true;

			// Remove any old "original" files in case we uploaded a new one with a different extension.
			foreach([Enums\ImageMimeType::JPG, Enums\ImageMimeType::PNG, Enums\ImageMimeType::WEBP] as $originalMimeType){
				$oldOriginalPath = $destinationBasePath . '-original' . $originalMimeType->GetFileExtension();
				if(is_file($oldOriginalPath)){
					@unlink($oldOriginalPath);
				}
			}

			foreach(['.jpg', '@2x.jpg', '.avif', '@2x.avif', $originalSuffix] as $suffix){
				copy($tempBasePath . $suffix, $destinationBasePath . $suffix);
			}
		}
		catch(\Safe\Exceptions\ExecException | \Safe\Exceptions\FilesystemException | \Safe\Exceptions\ImageException){
			if($filesWereInstalled){
				foreach($destinationPaths as $key => $destinationPath){
					try{
						$backupPath = $tempDirectory . '/backup-' . $key;
						if(is_file($backupPath)){
							copy($backupPath, $destinationPath);
						}
						elseif(is_file($destinationPath)){
							@unlink($destinationPath);
						}
					}
					catch(\Safe\Exceptions\FilesystemException){
						// Preserve the original exception.
					}
				}
			}

			throw new Exceptions\ImageUploadInvalidException('Failed to process hero image.');
		}
		finally{
			try{
				$tempFiles = glob($tempDirectory . '/*');
			}
			catch(\Safe\Exceptions\FilesystemException){
				$tempFiles = [];
			}

			foreach($tempFiles as $tempFile){
				try{
					@unlink($tempFile);
				}
				catch(\Safe\Exceptions\FilesystemException){
					// Pass.
				}
			}

			if(is_dir($tempDirectory)){
				try{
					@rmdir($tempDirectory);
				}
				catch(\Safe\Exceptions\FilesystemException){
					// Pass.
				}
			}
		}

	}

	/**
	 * Remove all generated hero image files for this blog post.
	 */
	private function RemoveHeroImage(): void{
		$basePath = WEB_ROOT . BLOG_POST_IMAGES_UPLOAD_PATH . '/' .$this->BlogPostId;
		foreach(['.jpg', '@2x.jpg', '.avif', '@2x.avif', '-original.jpg', '-original.png', '-original.webp'] as $suffix){
			if(is_file($basePath . $suffix)){
				@unlink($basePath . $suffix);
			}
		}
	}

	/**
	 * Add the related `Ebook`s for this `BlogPost`.
	 */
	private function AddEbooks(): void{
		if(sizeof($this->Ebooks) == 0){
			return;
		}

		$parameters = [];

		foreach($this->Ebooks as $sortOrder => $ebook){
			$parameters[] = $this->BlogPostId;
			$parameters[] = $ebook->EbookId;
			$parameters[] = $sortOrder;
		}

		Db::MultiInsert('insert into BlogPostEbooks (BlogPostId, EbookId, SortOrder) values (?, ?, ?)', $parameters);
	}

	public function FillFromRequestBody(): void{
		$this->PropertyFromRequest('Description');
		$this->PropertyFromRequest('HeroImageCaption');

		if(isset(Http::$Request->Body->Variables['blog-post-title'])){
			$this->Title = Http::$Request->Body->Get('blog-post-title') ?? '';
		}

		if(isset(Http::$Request->Body->Variables['blog-post-subtitle'])){
			$this->Subtitle = Http::$Request->Body->Get('blog-post-subtitle');
		}

		if(isset(Http::$Request->Body->Variables['blog-post-body'])){
			$this->Body = Http::$Request->Body->Get('blog-post-body');
		}

		// `PublishedAt` is always interpreted as being sent in the `America/Chicago` timezone.
		// Therefore we have to do some gymnastics to store it as UTC in our object.
		$published = Http::$Request->Body->Get('blog-post-published-at');
		if($published !== null){
			/** @throws void */
			$this->PublishedAt = (new DateTimeImmutable($published, SITE_TZ))->setTimezone(new DateTimeZone('UTC'));
		}
	}


	// ***********
	// ORM METHODS
	// ***********

	/**
	 * @throws Exceptions\BlogPostNotFoundException
	 */
	public static function GetByUrlTitle(?string $urlTitle): BlogPost{
		if($urlTitle === null){
			throw new Exceptions\BlogPostNotFoundException();
		}

		return Db::Query('
				select *
				from BlogPosts
				where UrlTitle = ?
			', [$urlTitle], BlogPost::class)[0] ?? throw new Exceptions\BlogPostNotFoundException();
	}

	/**
	 * @return array<BlogPost>
	 */
	public static function GetAllByIsPublished(): array{
		return Db::Query('
			select *
			from BlogPosts
			where PublishedAt < utc_timestamp()
			order by PublishedAt desc', [], BlogPost::class);
	}

	/**
	 * Get all blog posts for a specific page, sorted by descending publication or creation date.
	 *
	 * @return PaginatedResultsPage<BlogPost>
	 *
	 * @throws Exceptions\PageOutOfBoundsException If `$page` is outside of the result bounds.
	 */
	public static function GetPage(int $page = 1, int $perPage = 10, bool $includeUnpublished = false): PaginatedResultsPage{
		if($page <= 0){
			throw new Exceptions\PageOutOfBoundsException(realPageNumber: 1);
		}

		if($perPage <= 0 || $perPage > RESULTS_MAX_PER_PAGE){
			$perPage = 10;
		}

		$offset = (($page - 1) * $perPage);

		if($includeUnpublished){
			$blogPosts = Db::Query('
					select sql_calc_found_rows *
					from BlogPosts
					order by CreatedAt desc
					limit ?
					offset ?
				', [$perPage, $offset], BlogPost::class);
		}
		else{
			$blogPosts = Db::Query('
					select sql_calc_found_rows *
					from BlogPosts
					where PublishedAt < utc_timestamp()
					order by PublishedAt desc
					limit ?
					offset ?
				', [$perPage, $offset], BlogPost::class);
		}

		$count = Db::QueryInt('select found_rows()');
		$totalPages = (int)ceil($count / $perPage);

		if($totalPages > 0 && $page > $totalPages){
			throw new Exceptions\PageOutOfBoundsException(realPageNumber: $totalPages);
		}

		return new PaginatedResultsPage($blogPosts, $page, $count, $perPage);
	}
}
