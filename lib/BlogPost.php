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
 * @property User $User
 * @property-read string $Url
 * @property-read string $EditUrl
 * @property array<Ebook> $Ebooks
 * @property-read string $EbookIdentifiers A newline-separated list of `Ebook` identifiers related to this `BlogPost`.
 * @property-read HtmlFragment $Title
 * @property-write HtmlFragment|string $Title
 * @property-read ?HtmlFragment $Subtitle
 * @property-read ?string $Excerpt
 * @property-write HtmlFragment|string|null $Subtitle
 * @property-read ?HtmlFragment $Body May be `null` if the `BlogPost` is meant to redirect to a file, like the Public Domain Day posts.
 * @property-write HtmlFragment|string|null $Body
 * @property-read ?string $HeroImageUrl
 * @property-read ?string $HeroImage2xUrl
 * @property-read ?string $HeroImageAvifUrl
 * @property-read ?string $HeroImageAvif2xUrl
 */
class BlogPost{
	use Traits\Accessor;
	use Traits\PropertyFromRequest;

	public int $BlogPostId;
	public ?string $Description;
	public string $UrlTitle;
	public int $UserId;
	public DateTimeImmutable $Created;
	public DateTimeImmutable $Updated;
	public DateTimeImmutable $Published = NOW;
	public ?string $ImageCacheKey = null;

	protected string $_Url;
	protected string $_EditUrl;
	protected ?string $_Excerpt = null;
	protected User $_User;
	/** @var array<Ebook> */
	protected array $_Ebooks;
	protected string $_EbookIdentifiers;
	protected HtmlFragment $_Title; // TODO: Convert to property hook in PHP 8.4.
	protected ?HtmlFragment $_Subtitle; // TODO: Convert to property hook in PHP 8.4.
	protected ?HtmlFragment $_Body; // TODO: Convert to property hook in PHP 8.4.
	protected ?string $_HeroImageUrl;
	protected ?string $_HeroImage2xUrl;
	protected ?string $_HeroImageAvifUrl;
	protected ?string $_HeroImageAvif2xUrl;

	// *******
	// GETTERS
	// *******

	protected function GetExcerpt(): ?string{
		if(!isset($this->_Excerpt)){
			if($this->Body !== null){
				$this->_Excerpt = substr(strip_tags($this->Body), 0, 200) . '…';
			}
			elseif(isset($this->Subtitle)){
				$this->_Excerpt = strip_tags($this->Subtitle);
			}
		}

		return $this->_Excerpt;
	}

	protected function GetUrl(): string{
		return $this->_Url ??= '/blog/' . $this->UrlTitle;
	}

	protected function GetEditUrl(): string{
		return $this->_EditUrl ??= $this->Url . '/edit';
	}

	/**
	 * Return the URL of the 1x JPEG hero image, if one exists.
	 */
	protected function GetHeroImageUrl(): ?string{
		return $this->_HeroImageUrl ??= $this->ImageCacheKey !== null ? BLOG_POST_IMAGES_UPLOAD_PATH . '/' . $this->BlogPostId . '.jpg?v=' . $this->ImageCacheKey : null;
	}

	/**
	 * Return the URL of the 2x JPEG hero image, if one exists.
	 */
	protected function GetHeroImage2xUrl(): ?string{
		return $this->_HeroImage2xUrl ??= $this->ImageCacheKey !== null ? BLOG_POST_IMAGES_UPLOAD_PATH . '/' .$this->BlogPostId . '@2x.jpg?v=' . $this->ImageCacheKey : null;
	}

	/**
	 * Return the URL of the 1x AVIF hero image, if one exists.
	 */
	protected function GetHeroImageAvifUrl(): ?string{
		return $this->_HeroImageAvifUrl ??= $this->ImageCacheKey !== null ? BLOG_POST_IMAGES_UPLOAD_PATH . '/' .$this->BlogPostId . '.avif?v=' . $this->ImageCacheKey : null;
	}

	/**
	 * Return the URL of the 2x AVIF hero image, if one exists.
	 */
	protected function GetHeroImageAvif2xUrl(): ?string{
		return $this->_HeroImageAvif2xUrl ??= $this->ImageCacheKey !== null ? BLOG_POST_IMAGES_UPLOAD_PATH . '/' .$this->BlogPostId . '@2x.avif?v=' . $this->ImageCacheKey : null;
	}

	protected function GetEbookIdentifiers(): string{
		if(!isset($this->_EbookIdentifiers)){
			$this->_EbookIdentifiers = '';
			foreach($this->Ebooks as $ebook){
				$this->_EbookIdentifiers .= $ebook->Identifier . "\n";
			}

			$this->_EbookIdentifiers = trim($this->_EbookIdentifiers);
		}

		return $this->_EbookIdentifiers;
	}

	/**
	 * @return array<Ebook>
	 */
	protected function GetEbooks(): array{
		if(isset($this->BlogPostId)){
			return $this->_Ebooks ??= Db::Query('SELECT Ebooks.* from Ebooks inner join BlogPostEbooks using (EbookId) where BlogPostId = ? order by BlogPostEbooks.SortOrder asc', [$this->BlogPostId], Ebook::class);
		}
		else{
			return $this->_Ebooks ??= [];
		}
	}


	// *******
	// SETTERS
	// *******

	protected function SetTitle(string|HtmlFragment $string): void{
		$this->_Title = new HtmlFragment($string);
	}

	protected function SetSubtitle(string|HtmlFragment|null $string): void{
		if(isset($string)){
			$this->_Subtitle = new HtmlFragment($string);
		}
		else{
			$this->_Subtitle = $string;
		}
	}

	protected function SetBody(string|HtmlFragment|null $string): void{
		if(isset($string)){
			$this->_Body = new HtmlFragment($string);
		}
		else{
			$this->_Body = $string;
		}
	}


	// *******
	// METHODS
	// *******

	/**
	 * @param ?string $userIdentifier
	 * @param ?string $ebookIdentifiers A newline-separated list of ebook identifiers to merge with any ebook identifiers found in the body.
	 * @param ?string $heroImagePath The path to the uploaded hero image in the system temporary directory.
	 *
	 * @throws Exceptions\BlogPostInvalidException
	 */
	public function Validate(?string $userIdentifier = null, ?string $ebookIdentifiers = null, ?string $heroImagePath = null): void{
		$error = new Exceptions\BlogPostInvalidException();

		$this->Title ??= '';

		if($this->Title == ''){
			$error->Add(new Exceptions\BlogPostTitleRequiredException());
		}
		elseif(strlen($this->Title) > BLOG_POST_MAX_STRING_LENGTH){
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

		$this->_Ebooks = [];
		foreach($identifiers as $identifier){
			if($identifier == ''){
				continue;
			}

			try{
				$this->_Ebooks[] = Ebook::GetByIdentifier($identifier);
			}
			catch(Exceptions\EbookNotFoundException){
				$error->Add(new Exceptions\EbookNotFoundException('Ebook not found: ' . $identifier));
			}
		}

		$this->Description = trim($this->Description ?? '');
		if($this->Description == ''){
			$this->Description = null;
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

		if($heroImagePath !== null){
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
	public function Create(?string $userIdentifier = null, ?string $ebookIdentifiers = null, ?string $heroImagePath = null): void{
		$this->Validate($userIdentifier, $ebookIdentifiers, $heroImagePath);
		$this->Created = NOW;
		if($heroImagePath !== null){
			$this->ImageCacheKey = $this->GenerateImageCacheKey();
		}

		try{
			$this->BlogPostId = Db::QueryInt('
				INSERT into BlogPosts (UserId, Title, Subtitle, Description, UrlTitle, Body, ImageCacheKey, Published, Created)
				values (?,
				        ?,
				        ?,
				        ?,
				        ?,
				        ?,
				        ?,
				        ?,
				        ?)
				returning BlogPostId
			', [$this->UserId, $this->Title, $this->Subtitle, $this->Description, $this->UrlTitle, $this->Body, $this->ImageCacheKey, $this->Published, $this->Created]);
		}
		catch(Exceptions\DuplicateDatabaseKeyException){
			throw new Exceptions\BlogPostExistsException();
		}

		$this->AddEbooks();

		if($heroImagePath !== null){
			$this->WriteHeroImage($heroImagePath);
		}
	}

	/**
	 * @throws Exceptions\BlogPostInvalidException
	 * @throws Exceptions\BlogPostExistsException
	 * @throws Exceptions\ImageUploadInvalidException If the hero image cannot be processed.
	 */
	public function Save(?string $userIdentifier = null, ?string $ebookIdentifiers = null, ?string $heroImagePath = null, bool $removeHeroImage = false): void{
		$this->Validate($userIdentifier, $ebookIdentifiers, $heroImagePath);

		if($removeHeroImage){
			$this->ImageCacheKey = null;
		}
		elseif($heroImagePath !== null){
			$this->ImageCacheKey = $this->GenerateImageCacheKey();
		}

		if($removeHeroImage || $heroImagePath !== null){
			unset($this->_HeroImageUrl, $this->_HeroImage2xUrl, $this->_HeroImageAvifUrl, $this->_HeroImageAvif2xUrl);
		}

		try{
			Db::Query('
				UPDATE BlogPosts
				set UserId = ?, Title = ?, Subtitle = ?, Description = ?, UrlTitle = ?, Body = ?, ImageCacheKey = ?, Published = ? where BlogPostId = ?
			', [$this->UserId, $this->Title, $this->Subtitle, $this->Description, $this->UrlTitle, $this->Body, $this->ImageCacheKey, $this->Published, $this->BlogPostId]);
		}
		catch(Exceptions\DuplicateDatabaseKeyException){
			throw new Exceptions\BlogPostExistsException();
		}

		Db::Query('DELETE from BlogPostEbooks where BlogPostId = ?', [$this->BlogPostId]);

		$this->AddEbooks();

		if($removeHeroImage){
			$this->RemoveHeroImage();
		}
		elseif($heroImagePath !== null){
			$this->WriteHeroImage($heroImagePath);
		}

		// Reset the URL in case we changed the title.
		unset($this->_Url);
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
		$tempDirectory = sys_get_temp_dir() . '/' . uniqid('se-blog-hero-', true);

		try{
			mkdir($tempDirectory);

			$mimeType = Enums\ImageMimeType::FromFile($tempImagePath);
			if(!in_array($mimeType, [Enums\ImageMimeType::JPG, Enums\ImageMimeType::PNG, Enums\ImageMimeType::WEBP], true)){
				throw new Exceptions\ImageUploadInvalidException('Uploaded hero image must be a JPG, PNG, or WebP file.');
			}

			$tempBasePath = $tempDirectory . '/' . $this->BlogPostId;
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
				exec(escapeshellarg(SITE_ROOT . '/web/scripts/cavif') . ' --quiet --quality 50 ' . escapeshellarg($tempBasePath . $sourceSuffix) . ' --output ' . escapeshellarg($tempBasePath . $destinationSuffix), $output, $resultCode);
				if($resultCode !== 0){
					throw new Exceptions\ImageUploadInvalidException('Failed to process hero image.');
				}
			}

			$destinationBasePath = WEB_ROOT . BLOG_POST_IMAGES_UPLOAD_PATH . '/' .$this->BlogPostId;
			foreach(['.jpg', '@2x.jpg', '.avif', '@2x.avif'] as $suffix){
				copy($tempBasePath . $suffix, $destinationBasePath . $suffix);
			}
		}
		catch(\Safe\Exceptions\FilesystemException | \Safe\Exceptions\ImageException){
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
					unlink($tempFile);
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
		foreach(['.jpg', '@2x.jpg', '.avif', '@2x.avif'] as $suffix){
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

		Db::MultiInsert('INSERT into BlogPostEbooks (BlogPostId, EbookId, SortOrder) values (?, ?, ?)', $parameters);
	}

	public function FillFromRequestBody(): void{
		$this->PropertyFromRequest('Description');

		if(isset(Http::$Request->Body->Variables['blog-post-title'])){
			$this->Title = Http::$Request->Body->Get('blog-post-title') ?? '';
		}

		if(isset(Http::$Request->Body->Variables['blog-post-subtitle'])){
			$this->Subtitle = Http::$Request->Body->Get('blog-post-subtitle');
		}

		if(isset(Http::$Request->Body->Variables['blog-post-body'])){
			$this->Body = Http::$Request->Body->Get('blog-post-body');
		}

		// `Published` is always interpreted as being sent in the `America/Chicago` timezone.
		// Therefore we have to do some gymnastics to store it as UTC in our object.
		$published = Http::$Request->Body->Get('blog-post-published');
		if($published !== null){
			/** @throws void */
			$this->Published = (new DateTimeImmutable($published, SITE_TZ))->setTimezone(new DateTimeZone('UTC'));
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
				SELECT *
				from BlogPosts
				where UrlTitle = ?
			', [$urlTitle], BlogPost::class)[0] ?? throw new Exceptions\BlogPostNotFoundException();
	}

	/**
	 * @return array<BlogPost>
	 */
	public static function GetAllByIsPublished(): array{
		return Db::Query('
			SELECT *
			from BlogPosts
			where Published < utc_timestamp()
			order by Published desc', [], BlogPost::class);
	}

	/**
	 * @return array<BlogPost>
	 */
	public static function GetAllByCreated(): array{
		return Db::Query('
			SELECT *
			from BlogPosts
			order by Created desc', [], BlogPost::class);
	}
}
