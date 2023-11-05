<?
use Safe\DateTime;
use function Safe\apcu_fetch;
use function Safe\copy;
use function Safe\filesize;
use function Safe\getimagesize;
use function Safe\imagecopyresampled;
use function Safe\imagecreatefromjpeg;
use function Safe\imagecreatetruecolor;
use function Safe\imagejpeg;
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

	public $MuseumPage;
	public $PublicationYear;
	public $PublicationYearPage;
	public $CopyrightPage;
	public $ArtworkPage;

	// *******
	// GETTERS
	// *******

	/**
	 * @return string
	 */
	protected function GetUrlName(): string{
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
				throw new \Exceptions\InvalidArtworkException();
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
				throw new \Exceptions\InvalidArtworkException();
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
	/** @throws \Exceptions\ValidationException */
	protected function Validate(): void{
		$error = new Exceptions\ValidationException();

		if($this->Artist === null){
			$error->Add(new Exceptions\InvalidArtworkException());
		}

		if($this->Name === null || strlen($this->Name) === 0){
			$error->Add(new Exceptions\InvalidArtworkException());
		}

		if($this->UrlName === null || strlen($this->UrlName) === 0){
			$error->Add(new Exceptions\InvalidArtworkException());
		}

		if($this->Status !== null && !in_array($this->Status, [COVER_ARTWORK_STATUS_UNVERIFIED, COVER_ARTWORK_STATUS_APPROVED, COVER_ARTWORK_STATUS_DECLINED, COVER_ARTWORK_STATUS_IN_USE])){
			$error->Add(new Exceptions\InvalidArtworkException());
		}

		if($this->Status === COVER_ARTWORK_STATUS_IN_USE && $this->EbookWwwFilesystemPath === null){
			$error->Add(new Exceptions\InvalidArtworkException('Status in_use requires EbookWwwFilesystemPath'));
		}

		if($this->ArtworkTags !== null && count($this->_ArtworkTags) > 1000){
			$error->Add(new Exceptions\InvalidArtworkException());
		}

		$hasMuseumProof = $this->MuseumPage !== null && strlen($this->MuseumPage) > 0;
		$hasBookProof = $this->PublicationYear !== null
			&& ($this->PublicationYearPage !== null && strlen($this->PublicationYearPage) > 0)
			&& ($this->ArtworkPage !== null && strlen($this->ArtworkPage) > 0)
			&& ($this->CopyrightPage !== null && strlen($this->CopyrightPage) > 0);

		if(!$hasMuseumProof && !$hasBookProof){
			// In-use artwork has its public domain status tracked elsewhere, e.g., on the mailing list.
			if($this->Status !== COVER_ARTWORK_STATUS_IN_USE){
				$error->Add(new Exceptions\InvalidArtworkException('Must have proof of public domain status.'));
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

		if($error->HasExceptions){
			throw $error;
		}
	}

	/** @throws \Exceptions\InvalidImageUploadException */
	private function ValidateImageUpload(string $uploadPath): void{
		try{
			$uploadInfo = getimagesize($uploadPath);
		} catch (\Safe\Exceptions\ImageException $exception){
			throw new Exceptions\InvalidImageUploadException('Could not handle upload: ' . $exception->getMessage());
		}

		if($uploadInfo[2] !== IMAGETYPE_JPEG){
			throw new Exceptions\InvalidImageUploadException('Uploaded image must be a JPG file.');
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

	public static function Build(string $artistName, ?int $artistDeathYear, string $artworkName, ?int $completedYear,
				     ?bool $completedYearIsCirca, ?string $artworkTags, ?int $publicationYear,
				     ?string $publicationYearPage, ?string $copyrightPage, ?string $artworkPage,
				     ?string $museumPage): Artwork{
		$artist = new Artist();
		$artist->Name = $artistName;
		$artist->DeathYear = $artistDeathYear;

		$artwork = new Artwork();
		$artwork->Artist = $artist;
		$artwork->Name = $artworkName;
		$artwork->CompletedYear = $completedYear;
		$artwork->CompletedYearIsCirca = $completedYearIsCirca;
		$artwork->ArtworkTags = self::ParseArtworkTags($artworkTags);
		$artwork->Status = COVER_ARTWORK_STATUS_UNVERIFIED;
		$artwork->Created = new DateTime();
		$artwork->PublicationYear = $publicationYear;
		$artwork->PublicationYearPage = $publicationYearPage;
		$artwork->CopyrightPage = $copyrightPage;
		$artwork->ArtworkPage = $artworkPage;
		$artwork->MuseumPage = $museumPage;

		return $artwork;
	}

	/** @return array<ArtworkTag> */
	private static function ParseArtworkTags(?string $artworkTags): array{
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
	 * @throws \Exceptions\ValidationException
	 * @throws \Exceptions\InvalidImageUploadException
	 */
	public function Create(string $uploadPath): void{
		$log = new Log(ARTWORK_UPLOADS_LOG_FILE_PATH);

		$this->Validate();
		$this->ValidateImageUpload($uploadPath);

		try{
			$thumbPath = tempnam(WEB_ROOT . COVER_ART_UPLOAD_PATH, "tmp-thumb-");
			$imagePath = tempnam(WEB_ROOT . COVER_ART_UPLOAD_PATH, "tmp-image-");

			self::GenerateThumbnail($uploadPath, $thumbPath);
			if(!move_uploaded_file($uploadPath, $imagePath)){
				throw new \Safe\Exceptions\FilesystemException;
			}
		} catch (\Safe\Exceptions\FilesystemException|\Safe\Exceptions\ImageException $exception){
			$log->Write("Failed to create temp thumbnail or uploaded image.");
			$log->Write($exception);

			throw new \Exceptions\InvalidImageUploadException("Could not save uploaded image.");
		}

		/** @var ArtworkTag $artworkTag */
		foreach ($this->ArtworkTags as $artworkTag) {
			$artworkTag->GetOrCreate();
		}

		$this->Artist->GetOrCreate();
		$this->Insert();

		try{
			rename($thumbPath, WEB_ROOT . $this->ThumbUrl);
			rename($imagePath, WEB_ROOT . $this->ImageUrl);
		} catch (\Safe\Exceptions\FilesystemException $exception){
			$log->Write("Failed to store image or thumbnail for uploaded artwork [$this->ArtworkId].");
			$log->Write("Temporary image file at [$imagePath], temporary thumb file at [$thumbPath].");
			$log->Write($exception);

			throw new \Exceptions\InvalidImageUploadException("Your artwork was submitted but something went wrong. Please contact site administrator.");
		}
	}

	/**
	 * @throws \Exceptions\ValidationException
	 */
	public function CreateFromFilesystem(string $coverSourcePath): void{
		$this->Validate();
		$this->ValidateImageUpload($coverSourcePath);

		foreach ($this->ArtworkTags as $artworkTag) {
			$artworkTag->GetOrCreate();
		}

		if($this->Artist->ArtistId === null){
			$this->Artist->GetOrCreate();
		}

		$this->Insert();

		try{
			copy($coverSourcePath, WEB_ROOT . $this->ImageUrl);
			self::GenerateThumbnail($coverSourcePath, WEB_ROOT . $this->ThumbUrl);
		} catch (\Safe\Exceptions\FilesystemException|\Safe\Exceptions\ImageException $exception){
			throw new \Exceptions\InvalidImageUploadException("Couldn't create image and thumbnail at " . WEB_ROOT . $this->ImageUrl);
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

	private function Insert(): void{
		Db::Query('
			INSERT INTO Artworks (ArtistId, Name, UrlName, CompletedYear, CompletedYearIsCirca, Created, Status, MuseumPage,
			                      PublicationYear, PublicationYearPage, CopyrightPage, ArtworkPage, EbookWwwFilesystemPath)
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
				$this->Created, $this->Status, $this->MuseumPage, $this->PublicationYear, $this->PublicationYearPage,
				$this->CopyrightPage, $this->ArtworkPage, $this->EbookWwwFilesystemPath]
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
	}

	/**
	 * @throws \Exceptions\ValidationException
	 */
	public function Save(string $status): void{
		$this->Status = $status;
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
		$this->Save(COVER_ARTWORK_STATUS_IN_USE);
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
