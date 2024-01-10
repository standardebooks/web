<?

use Exceptions\InvalidUrlException;
use Safe\DateTime;
use function Safe\copy;
use function Safe\date;
use function Safe\exec;
use function Safe\getimagesize;
use function Safe\ini_get;
use function Safe\parse_url;
use function Safe\preg_match;
use function Safe\preg_replace;

/**
 * @property string $UrlName
 * @property string $Url
 * @property array<ArtworkTag> $Tags
 * @property Artist $Artist
 * @property string $ImageUrl
 * @property string $ThumbUrl
 * @property string $Thumb2xUrl
 * @property string $Dimensions
 * @property Ebook $Ebook
 * @property Museum $Museum
 * @property User $Submitter
 * @property User $Reviewer
 * @property ?ImageMimeType $MimeType
 * @property ?array<ArtworkTag> $_Tags
 */
class Artwork extends PropertiesBase{
	public ?string $Name = null;
	public ?int $ArtworkId = null;
	public ?int $ArtistId = null;
	public ?int $CompletedYear = null;
	public ?bool $CompletedYearIsCirca = null;
	public ?DateTime $Created = null;
	public ?DateTime $Updated = null;
	public ?string $Status = null;
	public ?string $EbookWwwFilesystemPath = null;
	public ?int $SubmitterUserId = null;
	public ?int $ReviewerUserId = null;
	public ?string $MuseumUrl = null;
	public ?int $PublicationYear = null;
	public ?string $PublicationYearPageUrl = null;
	public ?string $CopyrightPageUrl = null;
	public ?string $ArtworkPageUrl = null;
	public ?bool $IsPublishedInUs = null;
	public ?string $Exception = null;
	public ?string $Notes = null;
	protected ?string $_UrlName = null;
	protected ?string $_Url = null;
	protected $_Tags = null;
	protected ?Artist $_Artist = null;
	protected ?string $_ImageUrl = null;
	protected ?string $_ThumbUrl = null;
	protected ?string $_Thumb2xUrl = null;
	protected ?string $_Dimensions = null;
	protected ?Ebook $_Ebook = null;
	protected ?Museum $_Museum = null;
	protected ?User $_Submitter = null;
	protected ?User $_Reviewer = null;
	protected ?ImageMimeType $_MimeType = null;

	// *******
	// SETTERS
	// *******

	/**
	 * @param string|null|array<ArtworkTag> $tags
	 */
	protected function SetTags(string|array|null $tags): void{
		if($tags === null || is_array($tags)){
			$this->_Tags = $tags;
		}
		elseif(is_string($tags)){
			$tags = array_map('trim', explode(',', $tags));
			$tags = array_values(array_filter($tags));
			$tags = array_unique($tags);

			$this->_Tags = array_map(function ($str){
				$tag = new ArtworkTag();
				$tag->Name = $str;
				return $tag;
			}, $tags);
		}
	}

	// *******
	// GETTERS
	// *******

	protected function GetUrlName(): string{
		if($this->Name === null || $this->Name == ''){
			return '';
		}

		if($this->_UrlName === null){
			$this->_UrlName = Formatter::MakeUrlSafe($this->Name);
		}

		return $this->_UrlName;
	}

	protected function GetSubmitter(): ?User{
		if($this->_Submitter === null){
			try{
				$this->_Submitter = User::Get($this->SubmitterUserId);
			}
			catch(Exceptions\InvalidUserException){
				// Return null
			}
		}

		return $this->_Submitter;
	}

	protected function GetReviewer(): ?User{
		if($this->_Reviewer === null){
			try{
				$this->_Reviewer = User::Get($this->ReviewerUserId);
			}
			catch(Exceptions\InvalidUserException){
				// Return null
			}
		}

		return $this->_Reviewer;
	}

	protected function GetUrl(): string{
		if($this->_Url === null){
			$this->_Url = '/artworks/' . $this->Artist->UrlName . '/' . $this->UrlName;
		}

		return $this->_Url;
	}

	/**
	 * @return array<ArtworkTag>
	 */
	protected function GetTags(): array{
		if($this->_Tags === null){
			$this->_Tags = Db::Query('
							SELECT t.*
							from Tags t
							inner join ArtworkTags at using (TagId)
							where ArtworkId = ?
						', [$this->ArtworkId], 'ArtworkTag');
		}

		return $this->_Tags;
	}

	public function GetMuseum(): ?Museum{
		if($this->_Museum === null){
			try{
				$this->_Museum = Museum::GetByUrl($this->MuseumUrl);
			}
			catch(Exceptions\MuseumNotFoundException){
				// Pass
			}
		}

		return $this->_Museum;
	}

	public function ImplodeTags(): string{
		$tags = $this->Tags ?? [];
		$tags = array_column($tags, 'Name');
		return trim(implode(', ', $tags));
	}

	/**
	 * @throws \Exceptions\InvalidArtworkException
	 */
	protected function GetImageUrl(): string{
		if($this->_ImageUrl === null){
			if($this->ArtworkId === null || $this->MimeType === null){
				throw new Exceptions\InvalidArtworkException();
			}

			$this->_ImageUrl = COVER_ART_UPLOAD_PATH . $this->ArtworkId . $this->MimeType->GetFileExtension();
		}

		return $this->_ImageUrl;
	}

	/**
	 * @throws \Exceptions\ArtworkNotFoundException
	 */
	protected function GetThumbUrl(): string{
		if($this->_ThumbUrl === null){
			if($this->ArtworkId === null){
				throw new Exceptions\ArtworkNotFoundException();
			}

			$this->_ThumbUrl = COVER_ART_UPLOAD_PATH . $this->ArtworkId . '-thumb.jpg';
		}

		return $this->_ThumbUrl;
	}

	protected function GetThumb2xUrl(): string{
		if($this->_Thumb2xUrl === null){
			if($this->ArtworkId === null){
				throw new Exceptions\ArtworkNotFoundException();
			}

			$this->_Thumb2xUrl = COVER_ART_UPLOAD_PATH . $this->ArtworkId . '-thumb@2x.jpg';
		}

		return $this->_Thumb2xUrl;
	}

	protected function GetDimensions(): string{
		$this->_Dimensions = '';
		try{
			list($imageWidth, $imageHeight) = getimagesize(WEB_ROOT . $this->ImageUrl);
			if($imageWidth && $imageHeight){
				$this->_Dimensions = $imageWidth . ' × ' . $imageHeight;
			}
		}
		catch(Exception){
			// Image doesn't exist, return blank strin
		}

		return $this->_Dimensions;
	}

	protected function GetEbook(): ?Ebook{
		if($this->_Ebook === null){
			$this->_Ebook = Library::GetEbook(EBOOKS_DIST_PATH . str_replace('_', '/', $this->EbookWwwFilesystemPath ?? ''));
		}

		return $this->_Ebook;
	}

	protected function SetMimeType(null|string|ImageMimeType $mimeType): void{
		if(is_string($mimeType)){
			$this->_MimeType = ImageMimeType::tryFrom($mimeType);
		}
		else{
			$this->_MimeType = $mimeType;
		}
	}

	// *******
	// METHODS
	// *******
	/**
	 * @param array<mixed> $uploadedFile
	 * @throws \Exceptions\ValidationException
	 */
	protected function Validate(array &$uploadedFile = []): void{
		$error = new Exceptions\ValidationException();

		if($this->Artist === null){
			$error->Add(new Exceptions\InvalidArtworkException());
		}

		try{
			$this->Artist->Validate();
		}
		catch(Exceptions\ValidationException $ex){
			$error->Add($ex);
		}

		if($this->Exception !== null && trim($this->Exception) == ''){
			$this->Exception = null;
		}

		if($this->Notes !== null && trim($this->Notes) == ''){
			$this->Notes = null;
		}

		if($this->Name === null || $this->Name == ''){
			$error->Add(new Exceptions\ArtworkNameRequiredException());
		}

		if($this->Name !== null && strlen($this->Name) > COVER_ARTWORK_MAX_STRING_LENGTH){
			$error->Add(new Exceptions\StringTooLongException('Artwork Name'));
		}

		if($this->CompletedYear !== null && ($this->CompletedYear <= 0 || $this->CompletedYear > intval(date('Y')))){
			$error->Add(new Exceptions\InvalidCompletedYearException());
		}

		if($this->CompletedYear === null && $this->CompletedYearIsCirca){
			$this->CompletedYearIsCirca = false;
		}

		if($this->PublicationYear !== null && ($this->PublicationYear <= 0 || $this->PublicationYear > intval(date('Y')))){
			$error->Add(new Exceptions\InvalidPublicationYearException());
		}

		if($this->Status !== null && !in_array($this->Status, [COVER_ARTWORK_STATUS_UNVERIFIED, COVER_ARTWORK_STATUS_APPROVED, COVER_ARTWORK_STATUS_DECLINED, COVER_ARTWORK_STATUS_IN_USE])){
			$error->Add(new Exceptions\InvalidArtworkException('Invalid status.'));
		}

		if($this->Status === COVER_ARTWORK_STATUS_IN_USE && $this->EbookWwwFilesystemPath === null){
			$error->Add(new Exceptions\MissingEbookException());
		}

		if(count($this->Tags) == 0){
			// In-use artwork doesn't have user-provided tags.
			if($this->Status !== COVER_ARTWORK_STATUS_IN_USE){
				$error->Add(new Exceptions\TagsRequiredException());
			}
		}

		if(count($this->Tags) > COVER_ARTWORK_MAX_TAGS){
			$error->Add(new Exceptions\TooManyTagsException());
		}

		foreach($this->Tags as $tag){
			if(strlen($tag->Name) > COVER_ARTWORK_MAX_STRING_LENGTH){
				$error->Add(new Exceptions\StringTooLongException('Artwork Tag: '. $tag->Name));
			}
		}

		if($this->MuseumUrl !== null){
			if(strlen($this->MuseumUrl) > COVER_ARTWORK_MAX_STRING_LENGTH){
				$error->Add(new Exceptions\StringTooLongException('Link to an approved museum page'));
			}

			if($this->MuseumUrl == '' || filter_var($this->MuseumUrl, FILTER_VALIDATE_URL) === false){
				$error->Add(new Exceptions\InvalidMuseumUrlException());
			}

			// Don't allow unapproved museums
			try{
				Museum::GetByUrl($this->MuseumUrl);
			}
			catch(Exceptions\MuseumNotFoundException $ex){
				$error->Add($ex);
			}
		}

		if($this->PublicationYearPageUrl !== null){
			if(strlen($this->PublicationYearPageUrl) > COVER_ARTWORK_MAX_STRING_LENGTH){
				$error->Add(new Exceptions\StringTooLongException('Link to page with year of publication'));
			}

			if($this->PublicationYearPageUrl == '' || filter_var($this->PublicationYearPageUrl, FILTER_VALIDATE_URL) === false){
				$error->Add(new Exceptions\InvalidPublicationYearPageUrlException());
			}
			else{
				try{
					$this->PublicationYearPageUrl = $this->NormalizePageScanUrl($this->PublicationYearPageUrl);
				}
				catch(Exceptions\InvalidUrlException $ex){
					$error->Add($ex);
				}
			}
		}

		if($this->CopyrightPageUrl !== null){
			if(strlen($this->CopyrightPageUrl) > COVER_ARTWORK_MAX_STRING_LENGTH){
				$error->Add(new Exceptions\StringTooLongException('Link to page with copyright details'));
			}

			if($this->CopyrightPageUrl == '' || filter_var($this->CopyrightPageUrl, FILTER_VALIDATE_URL) === false){
				$error->Add(new Exceptions\InvalidCopyrightPageUrlException());
			}
			else{
				try{
					$this->CopyrightPageUrl = $this->NormalizePageScanUrl($this->CopyrightPageUrl);
				}
				catch(Exceptions\InvalidUrlException $ex){
					$error->Add($ex);
				}
			}
		}

		if($this->ArtworkPageUrl !== null){
			if(strlen($this->ArtworkPageUrl) > COVER_ARTWORK_MAX_STRING_LENGTH){
				$error->Add(new Exceptions\StringTooLongException('Link to page with artwork'));
			}

			if($this->ArtworkPageUrl == '' || filter_var($this->ArtworkPageUrl, FILTER_VALIDATE_URL) === false){
				$error->Add(new Exceptions\InvalidArtworkPageUrlException());
			}
			else{
				try{
					$this->ArtworkPageUrl = $this->NormalizePageScanUrl($this->ArtworkPageUrl);
				}
				catch(Exceptions\InvalidUrlException $ex){
					$error->Add($ex);
				}
			}
		}

		$hasMuseumProof = $this->MuseumUrl !== null && $this->MuseumUrl != '';
		$hasBookProof = $this->PublicationYear !== null
			&& ($this->PublicationYearPageUrl !== null && $this->PublicationYearPageUrl != '')
			&& ($this->ArtworkPageUrl !== null && $this->ArtworkPageUrl != '');

		if(!$hasMuseumProof && !$hasBookProof && $this->Exception === null){
			$error->Add(new Exceptions\MissingPdProofException());
		}

		if($this->MimeType === null){
			$error->Add(new Exceptions\InvalidMimeTypeException());
		}

		// Check the ebook www filesystem path.
		// We don't check if it exists, because the book might not be published yet.
		// But we do a basic check that the string includes one _. It might not include a dash, for example anonymous_poetry
		if($this->EbookWwwFilesystemPath !== null){
			if(mb_stripos($this->EbookWwwFilesystemPath, '_') === false){
				$error->Add(new Exceptions\InvalidEbookException('Invalid ebook. Expected file system slug like “c-s-lewis_poetry”.'));
			}
		}

		// Check for existing Artwork objects with the same URL but different Artwork IDs.
		try{
			$existingArtwork = Artwork::GetByUrl($this->Artist->UrlName, $this->UrlName);
			if($existingArtwork->ArtworkId != $this->ArtworkId){
				// Duplicate found, alert the user
				$error->Add(new Exceptions\ArtworkAlreadyExistsException());
			}
		}
		catch(Exceptions\ArtworkNotFoundException){
			// No duplicates found, continue
		}

		if(!is_writable(WEB_ROOT . COVER_ART_UPLOAD_PATH)){
			$error->Add(new Exceptions\InvalidImageUploadException('Upload path not writable.'));
		}

		if(!empty($uploadedFile) && $this->MimeType !== null){
			$uploadError = $uploadedFile['error'];
			if($uploadError > UPLOAD_ERR_OK){
				// see https://www.php.net/manual/en/features.file-upload.errors.php
				$message = match($uploadError){
					UPLOAD_ERR_INI_SIZE => 'Image upload too large (maximum ' . ini_get('upload_max_filesize') . ').',
					default => 'Image failed to upload (error code ' . $uploadError . ').',
				};
				$error->Add(new Exceptions\InvalidImageUploadException($message));
			}

			if(!is_uploaded_file($uploadedFile['tmp_name'])){
				$error->Add(new Exceptions\InvalidImageUploadException());
			}

			// Check for minimum dimensions
			list($imageWidth, $imageHeight) = getimagesize($uploadedFile['tmp_name']);
			if(!$imageWidth || !$imageHeight || $imageWidth < COVER_ARTWORK_IMAGE_MINIMUM_WIDTH || $imageHeight < COVER_ARTWORK_IMAGE_MINIMUM_HEIGHT){
				$error->Add(new Exceptions\ArtworkImageDimensionsTooSmallException());
			}
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	private function NormalizePageScanUrl(string $url): string{
		$outputUrl = $url;

		try{
			$parsedUrl = parse_url($url);
		}
		catch(Exception){
			throw new InvalidUrlException($url);
		}

		if(!is_array($parsedUrl)){
			throw new InvalidUrlException($url);
		}

		if(stripos($parsedUrl['host'], 'hathitrust.org') !== false){
			// https://babel.hathitrust.org/cgi/pt?id=hvd.32044034383265&seq=13
			if($parsedUrl['host'] != 'babel.hathitrust.org'){
				throw new Exceptions\InvalidHathiTrustUrlException();
			}

			if($parsedUrl['path'] != '/cgi/pt'){
				throw new Exceptions\InvalidHathiTrustUrlException();
			}

			parse_str($parsedUrl['query'] ?? '', $vars);

			if(!isset($vars['id']) || !isset($vars['seq']) || is_array($vars['id']) || is_array($vars['seq'])){
				throw new Exceptions\InvalidHathiTrustUrlException();
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'] . '?id=' . $vars['id'] . '&seq=' . $vars['seq'];
		}

		if(stripos($parsedUrl['host'], 'archive.org') !== false){
			// https://archive.org/details/royalacademypict1902roya/page/n9/mode/1up

			if($parsedUrl['host'] != 'archive.org'){
				throw new Exceptions\InvalidInternetArchiveUrlException();
			}

			if(!preg_match('|^/details/[^/]+?/page/[^/]+/mode/1up$|ius', $parsedUrl['path'])){
				throw new Exceptions\InvalidInternetArchiveUrlException();
			}

			$outputUrl = 'https://' . $parsedUrl['host'] . $parsedUrl['path'];
		}

		if(stripos($parsedUrl['host'], 'google.com') !== false){
			// Old style: https://books.google.com/books?id=mZpAAAAAYAAJ&pg=PA70-IA2
			// New style: https://www.google.com/books/edition/_/mZpAAAAAYAAJ?gbpv=1&pg=PA70-IA2

			if($parsedUrl['host'] == 'books.google.com'){
				// Old style, convert to new style

				if($parsedUrl['path'] != '/books'){
					throw new Exceptions\InvalidGoogleBooksUrlException();
				}

				parse_str($parsedUrl['query'] ?? '', $vars);

				if(!isset($vars['id']) || !isset($vars['pg']) || is_array($vars['id']) || is_array($vars['pg'])){
					throw new Exceptions\InvalidGoogleBooksUrlException();
				}

				$outputUrl = 'https://www.google.com/books/edition/_/' . $vars['id'] . '?gbpv=1&pg=' . $vars['pg'];
			}
			elseif($parsedUrl['host'] == 'www.google.com'){
				// New style

				if(!preg_match('|^/books/edition/[^/]+/[^/]+$|ius', $parsedUrl['path'])){
					throw new Exceptions\InvalidGoogleBooksUrlException();
				}

				preg_match('|^/books/edition/[^/]+/([^/]+)$|ius', $parsedUrl['path'], $matches);
				$id = $matches[1];

				parse_str($parsedUrl['query'] ?? '', $vars);

				if(!isset($vars['gbpv']) || $vars['gbpv'] !== '1' || !isset($vars['pg']) || is_array($vars['pg'])){
					throw new Exceptions\InvalidGoogleBooksUrlException();
				}

				$outputUrl = 'https://' . $parsedUrl['host'] . '/books/edition/_/' . $id . '?gbpv=' . $vars['gbpv'] . '&pg=' . $vars['pg'];
			}
			else{
				throw new Exceptions\InvalidGoogleBooksUrlException();
			}
		}

		return $outputUrl;
	}

	/**
	 * @param array<mixed> $uploadedFile
	 * @throws \Exceptions\ValidationException
	 * @throws \Exceptions\InvalidImageUploadException
	 */
	public function Create(array $uploadedFile): void{
		$this->Validate($uploadedFile);
		$this->Created = new DateTime();

		// Can't assign directly to $this->Tags because it's hidden behind a getter
		$tags = [];
		foreach($this->Tags as $artworkTag){
			$tags[] = ArtworkTag::GetOrCreate($artworkTag);
		}
		$this->Tags = $tags;

		$this->Artist = Artist::GetOrCreate($this->Artist);

		Db::Query('
			INSERT into
			Artworks (ArtistId, Name, UrlName, CompletedYear, CompletedYearIsCirca, Created, Status, SubmitterUserId, ReviewerUserId, MuseumUrl,
			                      PublicationYear, PublicationYearPageUrl, CopyrightPageUrl, ArtworkPageUrl, IsPublishedInUs,
			                      EbookWwwFilesystemPath, MimeType, Exception, Notes)
			values (?,
			        ?,
			        ?,
			        ?,
			        ?,
			        ?,
			        ?,
			        ?,
			        ?,
			        ?,
			        ?,
			        ?,
			        ?,
			        ?,
			        ?,
			        ?,
			        ?,
			        ?,
			        ?)
		', [$this->Artist->ArtistId, $this->Name, $this->UrlName, $this->CompletedYear, $this->CompletedYearIsCirca,
				$this->Created, $this->Status, $this->SubmitterUserId, $this->ReviewerUserId, $this->MuseumUrl, $this->PublicationYear, $this->PublicationYearPageUrl,
				$this->CopyrightPageUrl, $this->ArtworkPageUrl, $this->IsPublishedInUs, $this->EbookWwwFilesystemPath, $this->MimeType->value ?? null, $this->Exception, $this->Notes]
		);

		$this->ArtworkId = Db::GetLastInsertedId();

		foreach($this->Tags as $tag){
			Db::Query('
				INSERT into ArtworkTags (ArtworkId, TagId)
				values (?,
				        ?)
			', [$this->ArtworkId, $tag->TagId]);
		}

		// Save the source image and clean up metadata
		$imageUploadPath = $uploadedFile['tmp_name'];
		exec('exiftool -quiet -overwrite_original -all= ' . escapeshellarg($imageUploadPath));
		copy($imageUploadPath, WEB_ROOT . $this->ImageUrl);

		// Generate the thumbnails
		try{
			$image = new Image($imageUploadPath);
			$image->Resize(WEB_ROOT . $this->ThumbUrl, COVER_THUMBNAIL_WIDTH, COVER_THUMBNAIL_HEIGHT);
			$image->Resize(WEB_ROOT . $this->Thumb2xUrl, COVER_THUMBNAIL_WIDTH * 2, COVER_THUMBNAIL_HEIGHT * 2);
		}
		catch(\Safe\Exceptions\FilesystemException | \Safe\Exceptions\ImageException){
			throw new Exceptions\InvalidImageUploadException('Failed to generate thumbnail.');
		}
	}

	/**
	 * @throws \Exceptions\ValidationException
	 */
	public function Save(): void{
		$this->Validate();

		Db::Query('
			UPDATE Artworks
			set
			ArtistId = ?,
			Name = ?,
			UrlName = ?,
			CompletedYear = ?,
			CompletedYearIsCirca = ?,
			Created = ?,
			Status = ?,
			SubmitterUserId = ?,
			ReviewerUserId = ?,
			MuseumUrl = ?,
			PublicationYear = ?,
			PublicationYearPageUrl = ?,
			CopyrightPageUrl = ?,
			ArtworkPageUrl = ?,
			IsPublishedInUs = ?,
			EbookWwwFilesystemPath = ?,
			MimeType = ?,
			Exception = ?,
			Notes = ?
			where
			ArtworkId = ?
		', [$this->Artist->ArtistId, $this->Name, $this->UrlName, $this->CompletedYear, $this->CompletedYearIsCirca,
				$this->Created, $this->Status, $this->SubmitterUserId, $this->ReviewerUserId, $this->MuseumUrl, $this->PublicationYear, $this->PublicationYearPageUrl,
				$this->CopyrightPageUrl, $this->ArtworkPageUrl, $this->IsPublishedInUs, $this->EbookWwwFilesystemPath, $this->MimeType->value ?? null, $this->Exception, $this->Notes,
				$this->ArtworkId]
		);
	}

	public function MarkInUse(string $ebookWwwFilesystemPath): void{
		$this->EbookWwwFilesystemPath = $ebookWwwFilesystemPath;
		$this->Status = COVER_ARTWORK_STATUS_IN_USE;
		$this->Save();
	}

	public function Delete(): void{
		Db::Query('
			DELETE
			from ArtworkTags
			where ArtworkId = ?
		', [$this->ArtworkId]);

		Db::Query('
			DELETE
			from Artworks
			where ArtworkId = ?
		', [$this->ArtworkId]);
	}

	public function Contains(string $query): bool{
		$searchString = $this->Name;

		$searchString .= ' ' . $this->Artist->Name;
		$searchString .= ' ' . implode(' ', $this->Artist->AlternateSpellings);

		foreach($this->Tags as $tag){
			$searchString .= ' ' . $tag->Name;
		}

		// Remove diacritics and non-alphanumeric characters
		$searchString = trim(preg_replace('|[^a-zA-Z0-9 ]|ius', ' ', Formatter::RemoveDiacritics($searchString)));
		$query = trim(preg_replace('|[^a-zA-Z0-9 ]|ius', ' ', Formatter::RemoveDiacritics($query)));

		if($query == ''){
			return false;
		}

		if(mb_stripos($searchString, $query) !== false){
			return true;
		}

		return false;
	}

	// ***********
	// ORM METHODS
	// ***********

	/**
	 * @throws \Exceptions\ArtworkNotFoundException
	 */
	public static function Get(?int $artworkId): Artwork{
		if($artworkId === null){
			throw new Exceptions\ArtworkNotFoundException();
		}

		$result = Db::Query('
				SELECT *
				from Artworks
				where ArtworkId = ?
			', [$artworkId], 'Artwork');

		if(sizeof($result) == 0){
			throw new Exceptions\ArtworkNotFoundException();
		}

		return $result[0];
	}

	/**
         * Looks up an existing artwork regardless of status (unlike GetByUrl()) in order to
         * enforce that the Artist UrlName + Artwork UrlName combo is globally unique.
         */
	/**
	 * @throws \Exceptions\InvalidArtworkException
	 */
	public static function GetByUrl(?string $artistUrlName, ?string $artworkUrlName): Artwork{
		if($artistUrlName === null || $artworkUrlName === null){
			throw new Exceptions\ArtworkNotFoundException();
		}

		$result = Db::Query('
				SELECT Artworks.*
				from Artworks
				inner join Artists using (ArtistId)
				where Artists.UrlName = ? and Artworks.UrlName = ?
			', [$artistUrlName, $artworkUrlName], 'Artwork');

		if(sizeof($result) == 0){
			throw new Exceptions\ArtworkNotFoundException();
		}

		return $result[0];
	}
}
