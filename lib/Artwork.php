<?
use Safe\DateTime;
use function Safe\apcu_fetch;
use function Safe\chmod;
use function Safe\copy;
use function Safe\date;
use function Safe\filesize;
use function Safe\getimagesize;
use function Safe\imagecopyresampled;
use function Safe\imagecreatefromjpeg;
use function Safe\imagecreatetruecolor;
use function Safe\imagejpeg;
use function Safe\ini_get;
use function Safe\preg_replace;
use function Safe\rename;
use function Safe\sprintf;
use function Safe\tempnam;

/**
 * @property string $UrlName
 * @property string $Url
 * @property array<ArtworkTag> $ArtworkTags
 * @property string $ArtworkTagsImploded
 * @property Artist $Artist
 * @property string $ImageUrl
 * @property string $ThumbUrl
 * @property string $ImageSize
 * @property Ebook $Ebook
 */
class Artwork extends PropertiesBase{
	public $Name;
	public $ArtworkId;
	public $ArtistId;
	public $CompletedYear;
	public $CompletedYearIsCirca;
	public $Created;
	public $Status;
	public $EbookWwwFilesystemPath;
	protected $_UrlName;
	protected $_Url;
	protected $_ArtworkTags = null;
	protected $_Artist = null;
	protected $_ImageUrl = null;
	protected $_ThumbUrl = null;
	protected $_ImageSize = null;
	protected $_Ebook = null;

	public $MuseumUrl;
	public $PublicationYear;
	public $PublicationYearPageUrl;
	public $CopyrightPageUrl;
	public $ArtworkPageUrl;

	// *******
	// GETTERS
	// *******

	/**
	 * @return string
	 */
	protected function GetUrlName(): string{
		if($this->Name === null || $this->Name == ''){
			return '';
		}

		if($this->_UrlName === null){
			$this->_UrlName = Formatter::MakeUrlSafe($this->Name);
		}

		return $this->_UrlName;
	}

	/**
	 * @return string
	 */
	protected function GetUrl(): string{
		if($this->_Url === null){
			$this->_Url = '/artworks/' . $this->Artist->UrlName . '/' . $this->UrlName;
		}

		return $this->_Url;
	}

	/**
	 * @return array<ArtworkTag>
	 */
	protected function GetArtworkTags(): array{
		if($this->_ArtworkTags === null){
			$this->_ArtworkTags = Db::Query('
							SELECT t.*
							from Tags t
							inner join ArtworkTags at using (TagId)
							where ArtworkId = ?
						', [$this->ArtworkId], 'ArtworkTag');
		}

		return $this->_ArtworkTags;
	}

	protected function GetArtworkTagsImploded(): string{
		$tags = $this->ArtworkTags ?? [];
		$tags = array_column($tags, 'Name');
		return implode(', ', $tags);
	}

	/**
	 * @throws \Exceptions\InvalidArtworkException
	 */
	protected function GetImageUrl(): string{
		if($this->_ImageUrl == null){
			if($this->ArtworkId == null){
				throw new Exceptions\InvalidArtworkException();
			}

			$this->_ImageUrl = COVER_ART_UPLOAD_PATH . $this->ArtworkId . '.jpg';
		}

		return $this->_ImageUrl;
	}

	/**
	 * @throws \Exceptions\InvalidArtworkException
	 */
	protected function GetThumbUrl(): string{
		if($this->_ThumbUrl == null){
			if($this->ArtworkId == null){
				throw new Exceptions\InvalidArtworkException();
			}

			$this->_ThumbUrl = COVER_ART_UPLOAD_PATH . $this->ArtworkId . '.thumb.jpg';
		}

		return $this->_ThumbUrl;
	}

	/**
	 * @throws \Exceptions\InvalidArtworkException
	 */
	protected function GetImageSize(): string{
		try{
			$bytes = @filesize(WEB_ROOT . $this->ImageUrl);
			$sizes = 'BKMGTP';
			$factor = intval(floor((strlen((string)$bytes) - 1) / 3));
			$sizeNumber = sprintf('%.1f', $bytes / pow(1024, $factor));
			$sizeUnit = $sizes[$factor] ?? '';
			$this->_ImageSize = $sizeNumber . $sizeUnit;

			list($imageWidth, $imageHeight) = getimagesize(WEB_ROOT . $this->ImageUrl);
			if($imageWidth && $imageHeight){
				$this->_ImageSize .= ' (' . $imageWidth . ' × ' . $imageHeight . ')';
			}
		}
		catch(Exception){
			// Image doesn't exist
			$this->_ImageSize = '';
		}

		return $this->_ImageSize;
	}

	protected function GetEbook(): ?Ebook{
		if($this->EbookWwwFilesystemPath !== null){
			try{
				$key = 'ebook-' . $this->EbookWwwFilesystemPath;
				$this->_Ebook = apcu_exists($key) ? apcu_fetch($key) : null;
			}
			catch(Safe\Exceptions\ApcuException){
				// The Ebook with that filesystem path isn't cached.
			}
		}
		return $this->_Ebook;
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

		if($this->Name === null || $this->Name == ''){
			$error->Add(new Exceptions\ArtworkNameRequiredException ());
		}

		if($this->CompletedYear !== null && ($this->CompletedYear <=0 || $this->CompletedYear > intval(date('Y')))){
			$error->Add(new Exceptions\InvalidCompletedYearException());
		}

		if($this->PublicationYear !== null && ($this->PublicationYear <=0 || $this->PublicationYear > intval(date('Y')))){
			$error->Add(new Exceptions\InvalidPublicationYearException());
		}

		if($this->Status !== null && !in_array($this->Status, [COVER_ARTWORK_STATUS_UNVERIFIED, COVER_ARTWORK_STATUS_APPROVED, COVER_ARTWORK_STATUS_DECLINED, COVER_ARTWORK_STATUS_IN_USE])){
			$error->Add(new Exceptions\InvalidArtworkException());
		}

		if($this->Status === COVER_ARTWORK_STATUS_IN_USE && $this->EbookWwwFilesystemPath === null){
			$error->Add(new Exceptions\InvalidArtworkException('Status `in_use` requires EbookWwwFilesystemPath'));
		}

		if($this->ArtworkTags === null || count($this->_ArtworkTags) == 0 || count($this->_ArtworkTags) > 100){
			$error->Add(new Exceptions\TagsRequiredException());
		}

		if($this->MuseumUrl !== null && strlen($this->MuseumUrl) > 0 && filter_var($this->MuseumUrl, FILTER_VALIDATE_URL) === false){
			$error->Add(new Exceptions\InvalidMuseumUrlException());
		}

		if($this->PublicationYearPageUrl !== null && strlen($this->PublicationYearPageUrl) > 0 && filter_var($this->PublicationYearPageUrl, FILTER_VALIDATE_URL) === false){
			$error->Add(new Exceptions\InvalidPublicationYearPageUrlException());
		}

		if($this->ArtworkPageUrl !== null && strlen($this->ArtworkPageUrl) > 0 && filter_var($this->ArtworkPageUrl, FILTER_VALIDATE_URL) === false){
			$error->Add(new Exceptions\InvalidArtworkPageUrlException());
		}

		if($this->CopyrightPageUrl !== null && strlen($this->CopyrightPageUrl) > 0 && filter_var($this->CopyrightPageUrl, FILTER_VALIDATE_URL) === false){
			$error->Add(new Exceptions\InvalidCopyrightPageUrlException());
		}

		$hasMuseumProof = $this->MuseumUrl !== null && strlen($this->MuseumUrl) > 0;
		$hasBookProof = $this->PublicationYear !== null
			&& ($this->PublicationYearPageUrl !== null && strlen($this->PublicationYearPageUrl) > 0)
			&& ($this->ArtworkPageUrl !== null && strlen($this->ArtworkPageUrl) > 0)
			&& ($this->CopyrightPageUrl !== null && strlen($this->CopyrightPageUrl) > 0);

		if(!$hasMuseumProof && !$hasBookProof){
			// In-use artwork has its public domain status tracked elsewhere, e.g., on the mailing list.
			if($this->Status !== COVER_ARTWORK_STATUS_IN_USE){
				$error->Add(new Exceptions\InvalidArtworkException('Missing proof of public domain status.'));
			}
		}

		$existingArtwork = Artwork::GetByUrlPath($this->Artist->UrlName, $this->UrlName);
		// Check for Artwork objects with the same URL but different Artwork IDs.
		if($existingArtwork !== null && ($existingArtwork->ArtworkId !== $this->ArtworkId)){
			// Unverified and declined artwork can match an existing object. Approved and In Use artwork cannot.
			if(!in_array($this->Status, [COVER_ARTWORK_STATUS_UNVERIFIED, COVER_ARTWORK_STATUS_DECLINED])){
				$error->Add(new Exceptions\InvalidArtworkException('Artwork already exisits: ' . SITE_URL . $existingArtwork->Url));
			}
		}

		if(!is_writable(WEB_ROOT . COVER_ART_UPLOAD_PATH)){
			$error->Add(new Exceptions\InvalidImageUploadException('Upload path not writable.'));
		}

		if(!empty($uploadedFile)){
			$uploadError = $uploadedFile['error'];
			if($uploadError > UPLOAD_ERR_OK){
				// see https://www.php.net/manual/en/features.file-upload.errors.php
				$message = match ($uploadError){
					UPLOAD_ERR_INI_SIZE => 'Image upload too large (maximum ' . ini_get('upload_max_filesize') . ').',
					default => 'Image failed to upload (error code ' . $uploadError . ').',
				};
				$error->Add(new Exceptions\InvalidImageUploadException($message));
			}
			else{
				$uploadPath = $uploadedFile['tmp_name'];
				$uploadInfo = [];
				try{
					$uploadInfo = getimagesize($uploadPath);
				}
				catch(\Safe\Exceptions\ImageException $exception){
					$error->Add(new Exceptions\InvalidImageUploadException('Could not handle upload: ' . $exception->getMessage()));
				}

				if(!empty($uploadInfo) && $uploadInfo[2] !== IMAGETYPE_JPEG){
					$error->Add(new Exceptions\InvalidImageUploadException('Uploaded image must be a JPG file.'));
				}

				$thumbPath = tempnam(sys_get_temp_dir(), 'tmp-thumb-');
				$uploadedFile['thumbPath'] = $thumbPath;
				try{
					chmod($thumbPath, 0644);
					self::GenerateThumbnail($uploadPath, $thumbPath);
				}
				catch(\Safe\Exceptions\FilesystemException | \Safe\Exceptions\ImageException $exception){
					$error->Add(new Exceptions\InvalidImageUploadException('Failed to generate thumbnail.'));
				}
			}
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	// ***********
	// ORM METHODS
	// ***********

	public static function Get(?int $artworkId): Artwork{
		if($artworkId === null){
			throw new Exceptions\InvalidArtworkException();
		}

		$result = Db::Query('
				SELECT *
				from Artworks
				where ArtworkId = ?
			', [$artworkId], 'Artwork');

		if(sizeof($result) == 0){
			throw new Exceptions\InvalidArtworkException();
		}

		return $result[0];
	}

	public static function GetByUrlPath(string $artistUrlName, string $artworkUrlName): ?Artwork{
		$result = Db::Query('
				SELECT Artworks.*
				from Artworks
				inner join Artists using (ArtistId)
				where Status in ("approved", "in_use") and
				Artists.UrlName = ? and Artworks.UrlName = ?
			', [$artistUrlName, $artworkUrlName], 'Artwork');

		if(sizeof($result) == 0){
			return null;
		}

		return $result[0];
	}

	/** @return array<ArtworkTag> */
	public static function ParseArtworkTags(?string $artworkTags): array{
		if(!$artworkTags) return array();

		$artworkTags = array_map('trim', explode(',', $artworkTags));
		$artworkTags = array_values(array_filter($artworkTags));
		$artworkTags = array_unique($artworkTags);

		return array_map(function ($str){
			$artworkTag = new ArtworkTag();
			$artworkTag->Name = $str;
			return $artworkTag;
		}, $artworkTags);
	}

	/**
	 * @param array<mixed> $uploadedFile
	 * @throws \Exceptions\ValidationException
	 * @throws \Exceptions\InvalidImageUploadException
	 */
	public function Create(array $uploadedFile, bool $copyFile = false): void{
		$this->Validate($uploadedFile);
		$this->Created = new DateTime();

		foreach ($this->ArtworkTags as $artworkTag) {
			$artworkTag->GetOrCreate();
		}

		$this->Artist->GetOrCreate();
		Db::Query('
			INSERT INTO Artworks (ArtistId, Name, UrlName, CompletedYear, CompletedYearIsCirca, Created, Status, MuseumUrl,
			                      PublicationYear, PublicationYearPageUrl, CopyrightPageUrl, ArtworkPageUrl, EbookWwwFilesystemPath)
			VALUES (?,
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
				$this->Created, $this->Status, $this->MuseumUrl, $this->PublicationYear, $this->PublicationYearPageUrl,
				$this->CopyrightPageUrl, $this->ArtworkPageUrl, $this->EbookWwwFilesystemPath]
		);

		$this->ArtworkId = Db::GetLastInsertedId();

		if($this->_ArtworkTags !== null){
			foreach($this->ArtworkTags as $tag){
				Db::Query('
					INSERT into ArtworkTags (ArtworkId, TagId)
					values (?,
					        ?)
				', [$this->ArtworkId, $tag->TagId]);
			}
		}

		try{
			rename($uploadedFile['thumbPath'], WEB_ROOT . $this->ThumbUrl);
			if($copyFile){
				copy($uploadedFile['tmp_name'], WEB_ROOT . $this->ImageUrl);
			}
			else{
				if(!move_uploaded_file($uploadedFile['tmp_name'], WEB_ROOT . $this->ImageUrl)){
					throw new \Safe\Exceptions\FilesystemException('Failed to save uploaded image.');
				}
			}
		}
		catch(\Safe\Exceptions\FilesystemException $exception){
			$log = new Log(ARTWORK_UPLOADS_LOG_FILE_PATH);
			$log->Write('Failed to store image or thumbnail for uploaded artwork ' . $this->ArtworkId . '.');
			$log->Write('Temporary image file at ' . $uploadedFile['tmp_name'] . ', temporary thumb file at ' . $uploadedFile['thumbPath'] . '.');
			$log->Write($exception);

			throw new Exceptions\InvalidImageUploadException('Your artwork was submitted but something went wrong. Please contact site administrator.');
		}
	}

	/**
	 * @throws \Safe\Exceptions\ImageException
	 */
	private static function GenerateThumbnail(string $srcImagePath, string $dstThumbPath): void{
		$uploadInfo = getimagesize($srcImagePath);

		$src_w = $uploadInfo[0];
		$src_h = $uploadInfo[1];

		if($src_h > $src_w){
			$dst_h = COVER_THUMBNAIL_SIZE;
			$dst_w = intval($dst_h * ($src_w / $src_h));
		}
		else{
			$dst_w = COVER_THUMBNAIL_SIZE;
			$dst_h = intval($dst_w * ($src_h / $src_w));
		}

		$srcImage = imagecreatefromjpeg($srcImagePath);
		$thumbImage = imagecreatetruecolor($dst_w, $dst_h);

		imagecopyresampled($thumbImage, $srcImage, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
		imagejpeg($thumbImage, $dstThumbPath);
	}

	/**
	 * @throws \Exceptions\ValidationException
	 */
	public function Save(): void{
		$this->Validate();

		Db::Query('
			UPDATE Artworks
			set Status = ?,
			EbookWwwFilesystemPath = ?
			where ArtworkId = ?
		', [$this->Status, $this->EbookWwwFilesystemPath, $this->ArtworkId]);
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

	/**
	 *  Browsable Artwork can be displayed publically, e.g., at /artworks.
	 *  Unverified and declined Artwork shouldn't be browsable.
	 *  @return array<Artwork>
	 */
	public static function GetBrowsable(): array{
		return Db::Query('
			SELECT *
			FROM Artworks
			WHERE Status IN ("approved", "in_use")', [], 'Artwork');
	}

	public function Contains(string $query): bool{
		$searchString = $this->Name;

		$searchString .= ' ' . $this->Artist->Name;
		$searchString .= ' ' . implode(' ', $this->Artist->AlternateSpellings);

		foreach($this->ArtworkTags as $tag){
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
}
