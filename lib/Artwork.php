<?
use Safe\DateTime;
use function Safe\filesize;
use function Safe\getimagesize;
use function Safe\imagecreatefromjpeg;
use function Safe\imagecreatetruecolor;
use function Safe\imagejpeg;
use function Safe\rename;
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
		if ($this->_ImageUrl == null){
			if ($this->ArtworkId == null){
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
		if ($this->_ThumbUrl == null){
			if ($this->ArtworkId == null){
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
			$factor = floor((strlen($bytes) - 1) / 3);
			$sizeNumber = sprintf('%.1f', $bytes / pow(1024, $factor));
			$sizeUnit = $sizes[$factor] ?? '';
			$this->_ImageSize = $sizeNumber . $sizeUnit;
		}
		catch(Exception $ex){
			// Image doesn't exist
			$this->_ImageSize = '';
		}

		return $this->_ImageSize;
	}

	protected function GetEbook(): Ebook{
		$this->_Ebook = new Ebook();
		if ($this->EbookWwwFilesystemPath !== null){
			try{
				$this->_Ebook = apcu_fetch('ebook-' . $this->EbookWwwFilesystemPath);
			}
			catch(Safe\Exceptions\ApcuException $ex){
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

		if($this->Status !== null && !in_array($this->Status, ['unverified', 'approved', 'declined', 'in_use'])){
			$error->Add(new Exceptions\InvalidArtworkException());
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
			$error->Add(new Exceptions\InvalidArtworkException('Must have proof of public domain status.'));
		}

		$existingArtwork = Artwork::GetByUrlPath($this->Artist->UrlName, $this->UrlName);
		// Unverified and declined artwork can match an existing object. Approved and In Use artwork cannot.
		if($existingArtwork !== null && !in_array($this->Status, ['unverified', 'declined'])){
			$error->Add(new Exceptions\InvalidArtworkException('Artwork already exisits: ' . SITE_URL . $existingArtwork->Url));
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	/** @throws \Exceptions\InvalidImageUploadException */
	private function ValidateImageUpload(string $uploadPath): void{
		$uploadInfo = getimagesize($uploadPath);

		if ($uploadInfo === false){
			throw new Exceptions\InvalidImageUploadException();
		}

		if ($uploadInfo[2] !== IMAGETYPE_JPEG){
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

	public static function GetByUrlPath($artistUrlName, $artworkUrlName): ?Artwork{
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
				     bool $completedYearIsCirca, ?string $artworkTags, ?int $publicationYear,
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
		$artwork->Status = 'unverified';
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
		if (!$artworkTags) return array();

		$artworkTags = array_map('trim', explode(',', $artworkTags)) ?? array();
		$artworkTags = array_values(array_filter($artworkTags)) ?? array();
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
	 * @throws \Safe\Exceptions\ImageException
	 */
	private static function GenerateThumbnail(string $srcImagePath, string $dstThumbPath): void{
		$uploadInfo = getimagesize($srcImagePath);

		$src_w = $uploadInfo[0];
		$src_h = $uploadInfo[1];

		if ($src_h > $src_w){
			$dst_h = COVER_THUMBNAIL_SIZE;
			$dst_w = intval($dst_h * ($src_w / $src_h));
		} else{
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
			INSERT INTO Artworks (ArtistId, Name, UrlName, CompletedYear, CompletedYearIsCirca, Created, MuseumPage,
			                      PublicationYear, PublicationYearPage, CopyrightPage, ArtworkPage)
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
			        ?)
		', [$this->Artist->ArtistId, $this->Name, $this->UrlName, $this->CompletedYear, $this->CompletedYearIsCirca,
				$this->Created, $this->MuseumPage, $this->PublicationYear, $this->PublicationYearPage,
				$this->CopyrightPage, $this->ArtworkPage]
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
			set Status = ?
			where ArtworkId = ?
		', [$this->Status, $this->ArtworkId]);
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
