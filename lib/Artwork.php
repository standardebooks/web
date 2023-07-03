<?
use Safe\DateTime;
use function Safe\filesize;

/**
 * @property string $UrlName
 * @property string $Slug
 * @property array<ArtworkTag> $ArtworkTags
 * @property Artist $Artist
 * @property string $ImageUrl
 * @property string $ThumbUrl
 * @property string $ImageSize
 */
class Artwork extends PropertiesBase{
	public $Name;
	public $ArtworkId;
	public $ArtistId;
	public $CompletedYear;
	public $CompletedYearIsCirca;
	public $Created;
	public $Status;
	protected $_UrlName;
	protected $_Slug;
	protected $_ArtworkTags = null;
	protected $_Artist = null;
	protected $_ImageUrl = null;
	protected $_ThumbUrl = null;
	protected $_ImageSize = null;

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
	protected function GetSlug(): string{
		if($this->_Slug === null){
			$this->_Slug = $this->Artist->UrlName . '/' . $this->UrlName;
		}

		return $this->_Slug;
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

	// *******
	// METHODS
	// *******
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

	/**
	 * @throws \Exceptions\ValidationException
	 */
	public function Create(): void{
		$this->Validate();
		$this->Created = new DateTime();
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
	public function Save(): void{
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
