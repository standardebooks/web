<?
use Safe\DateTime;
use function Safe\copy;
use function Safe\date;
use function Safe\exec;
use function Safe\filesize;
use function Safe\getimagesize;
use function Safe\ini_get;
use function Safe\preg_replace;
use function Safe\sprintf;

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
 * @property ?ImageMimeType $MimeType
 */
class Artwork extends PropertiesBase{
	public $Name;
	public $ArtworkId;
	public $ArtistId;
	public $CompletedYear;
	public $CompletedYearIsCirca;
	public $Created;
	public $Updated;
	public $Status;
	public $EbookWwwFilesystemPath;
	public $ReviewerUserId;
	public $MuseumUrl;
	public $PublicationYear;
	public $PublicationYearPageUrl;
	public $CopyrightPageUrl;
	public $ArtworkPageUrl;
	public $IsPublishedInUs;
	public $Exception;
	protected $_UrlName;
	protected $_Url;
	protected $_AdminUrl;
	protected $_Tags = null;
	protected $_Artist = null;
	protected $_ImageUrl = null;
	protected $_ThumbUrl = null;
	protected $_Thumb2xUrl = null;
	protected $_Dimensions = null;
	protected $_Ebook = null;
	protected $_Museum = null;
	protected ?ImageMimeType $_MimeType = null;

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

	protected function GetUrl(): string{
		if($this->_Url === null){
			$this->_Url = '/artworks/' . $this->Artist->UrlName . '/' . $this->UrlName;
		}

		return $this->_Url;
	}

	protected function GetAdminUrl(): string{
		if($this->_AdminUrl === null){
			$this->_AdminUrl = '/admin/artworks/' . $this->ArtworkId;
		}

		return $this->_AdminUrl;
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
			$this->_Museum = Museum::GetByUrl($this->MuseumUrl);
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
		try{

			list($imageWidth, $imageHeight) = getimagesize(WEB_ROOT . $this->ImageUrl);
			if($imageWidth && $imageHeight){
				$this->_Dimensions .= $imageWidth . ' × ' . $imageHeight;
			}
		}
		catch(Exception){
			// Image doesn't exist
			$this->_Dimensions = '';
		}

		return $this->_Dimensions;
	}

	protected function GetEbook(): ?Ebook{
		if($this->_Ebook === null){
			$this->_Ebook = Library::GetEbook($this->EbookWwwFilesystemPath);
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

		if($this->Tags === null || count($this->_Tags) == 0){
			// In-use artwork doesn't have user-provided tags.
			if($this->Status !== COVER_ARTWORK_STATUS_IN_USE){
				$error->Add(new Exceptions\TagsRequiredException());
			}
		}

		if($this->Tags !== null && count($this->_Tags) > COVER_ARTWORK_MAX_TAGS){
			$error->Add(new Exceptions\TooManyTagsException());
		}

		foreach($this->Tags as $tag){
			if(strlen($tag->Name) > COVER_ARTWORK_MAX_STRING_LENGTH){
				$error->Add(new Exceptions\StringTooLongException('Artwork Tag: '. $tag->Name));
			}
		}

		if($this->MuseumUrl !== null && strlen($this->MuseumUrl) > 0 && filter_var($this->MuseumUrl, FILTER_VALIDATE_URL) === false){
			$error->Add(new Exceptions\InvalidMuseumUrlException());
		}

		if($this->MuseumUrl !== null && strlen($this->MuseumUrl) > COVER_ARTWORK_MAX_STRING_LENGTH){
			$error->Add(new Exceptions\StringTooLongException('Link to an approved museum page'));
		}

		if($this->PublicationYearPageUrl !== null && strlen($this->PublicationYearPageUrl) > 0 && filter_var($this->PublicationYearPageUrl, FILTER_VALIDATE_URL) === false){
			$error->Add(new Exceptions\InvalidPublicationYearPageUrlException());
		}

		if($this->PublicationYearPageUrl !== null && strlen($this->PublicationYearPageUrl) > COVER_ARTWORK_MAX_STRING_LENGTH){
			$error->Add(new Exceptions\StringTooLongException('Link to page with year of publication'));
		}

		if($this->CopyrightPageUrl !== null && strlen($this->CopyrightPageUrl) > 0 && filter_var($this->CopyrightPageUrl, FILTER_VALIDATE_URL) === false){
			$error->Add(new Exceptions\InvalidCopyrightPageUrlException());
		}

		if($this->CopyrightPageUrl !== null && strlen($this->CopyrightPageUrl) > COVER_ARTWORK_MAX_STRING_LENGTH){
			$error->Add(new Exceptions\StringTooLongException('Link to page with copyright details'));
		}

		if($this->ArtworkPageUrl !== null && strlen($this->ArtworkPageUrl) > 0 && filter_var($this->ArtworkPageUrl, FILTER_VALIDATE_URL) === false){
			$error->Add(new Exceptions\InvalidArtworkPageUrlException());
		}

		if($this->ArtworkPageUrl !== null && strlen($this->ArtworkPageUrl) > COVER_ARTWORK_MAX_STRING_LENGTH){
			$error->Add(new Exceptions\StringTooLongException('Link to page with artwork'));
		}

		$hasMuseumProof = $this->MuseumUrl !== null && $this->MuseumUrl != '';
		$hasBookProof = $this->PublicationYear !== null
			&& ($this->PublicationYearPageUrl !== null && $this->PublicationYearPageUrl != '')
			&& ($this->ArtworkPageUrl !== null && $this->ArtworkPageUrl != '');

		if(!$hasMuseumProof && !$hasBookProof && $this->Exception === null){
			// In-use artwork has its public domain status tracked elsewhere, e.g., on the mailing list.
			if($this->Status !== COVER_ARTWORK_STATUS_IN_USE){
				$error->Add(new Exceptions\MissingPdProofException());
			}
		}

		if($this->MimeType === null){
			$error->Add(new Exceptions\InvalidMimeTypeException());
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
				throw new Exceptions\InvalidImageUploadException();
			}
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	/** @return array<ArtworkTag> */
	public static function ParseTags(?string $tags): array{
		if(!$tags) return [];

		$tags = array_map('trim', explode(',', $tags));
		$tags = array_values(array_filter($tags));
		$tags = array_unique($tags);

		return array_map(function ($str){
			$tag = new ArtworkTag();
			$tag->Name = $str;
			return $tag;
		}, $tags);
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
			Artworks (ArtistId, Name, UrlName, CompletedYear, CompletedYearIsCirca, Created, Status, ReviewerUserId, MuseumUrl,
			                      PublicationYear, PublicationYearPageUrl, CopyrightPageUrl, ArtworkPageUrl, IsPublishedInUs,
			                      EbookWwwFilesystemPath, MimeType, Exception)
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
			        ?)
		', [$this->Artist->ArtistId, $this->Name, $this->UrlName, $this->CompletedYear, $this->CompletedYearIsCirca,
				$this->Created, $this->Status, $this->ReviewerUserId, $this->MuseumUrl, $this->PublicationYear, $this->PublicationYearPageUrl,
				$this->CopyrightPageUrl, $this->ArtworkPageUrl, $this->IsPublishedInUs, $this->EbookWwwFilesystemPath, $this->MimeType->value ?? null, $this->Exception]
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
		exec('exiftool -quiet -overwrite-_original -all= ' . escapeshellarg($imageUploadPath));
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
			ReviewerUserId = ?,
			MuseumUrl = ?,
			PublicationYear = ?,
			PublicationYearPageUrl = ?,
			CopyrightPageUrl = ?,
			ArtworkPageUrl = ?,
			IsPublishedInUs = ?,
			EbookWwwFilesystemPath = ?,
			MimeType = ?,
			Exception = ?
			where
			ArtworkId = ?
		', [$this->Artist->ArtistId, $this->Name, $this->UrlName, $this->CompletedYear, $this->CompletedYearIsCirca,
				$this->Created, $this->Status, $this->ReviewerUserId, $this->MuseumUrl, $this->PublicationYear, $this->PublicationYearPageUrl,
				$this->CopyrightPageUrl, $this->ArtworkPageUrl, $this->IsPublishedInUs, $this->EbookWwwFilesystemPath, $this->MimeType->value ?? null, $this->Exception,
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
	public static function GetByUrl(string $artistUrlName, string $artworkUrlName): Artwork{
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

	/**
         * Gets a publically available Artwork, i.e., with approved or in_use status.
         * Artwork with status unverifed and declined aren't available by URL.
         */
	/**
	 * @throws \Exceptions\InvalidArtworkException
	 */
	public static function GetByUrlAndIsApproved(string $artistUrlName, string $artworkUrlName): Artwork{
		$result = Db::Query('
				SELECT Artworks.*
				from Artworks
				inner join Artists using (ArtistId)
				where Status in ("approved", "in_use") and
				Artists.UrlName = ? and Artworks.UrlName = ?
			', [$artistUrlName, $artworkUrlName], 'Artwork');

		if(sizeof($result) == 0){
			throw new Exceptions\ArtworkNotFoundException();
		}

		return $result[0];
	}
}
