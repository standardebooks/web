<?
use Safe\DateTime;

/**
 * @property string $UrlName
 * @property array<ArtworkTag> $ArtworkTags
 * @property Artist $Artist
 */
class Artwork extends PropertiesBase{
	public $Name;
	public $ArtworkId;
	public $CompletedYear;
	public $CompletedYearIsCirca;
	public $ImageFilesystemPath;
	public $Created;
	public $Status;
	protected $_UrlName;
	protected $_ArtworkTags = null;
	protected $_Artist = null;

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
	 * @return Artist
	 */
	protected function GetArtist(): Artist{
		if($this->_Artist === null){
			$result = Db::Query('
					SELECT *
					from Artists
					where ArtistId = ?
				', [$this->_Artist->ArtistId], 'Artist');
			if(sizeof($result) == 0){
				throw new Exceptions\InvalidArtistException();
			}
			$this->_Artist = $result[0];
		}

		return $this->_Artist;
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

		if($this->ImageFilesystemPath === null || strlen($this->ImageFilesystemPath) === 0){
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

    /**
     * @throws \Exceptions\ValidationException
     */
	public function Create(): void {
		$this->Validate();
		$this->Created = new DateTime();
		Db::Query('
			INSERT INTO Artworks (ArtistId, Name, UrlName, CompletedYear, CompletedYearIsCirca, ImageFilesystemPath, 
                      			Created, MuseumPage, PublicationYear, PublicationYearPage, CopyrightPage, ArtworkPage)
			VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
		', [$this->Artist->ArtistId, $this->Name, $this->UrlName, $this->CompletedYear, $this->CompletedYearIsCirca,
				$this->ImageFilesystemPath, $this->Created, $this->MuseumPage, $this->PublicationYear,
				$this->PublicationYearPage, $this->CopyrightPage, $this->ArtworkPage]
		);

		$this->ArtworkId = Db::GetLastInsertedId();

		if($this->_ArtworkTags !== null){
			for ($i = 0; $i < count($this->ArtworkTags); $i++){
				Db::Query('
					INSERT into ArtworkTags (ArtworkId, TagId)
					values (?,
					        ?)
				', [$this->ArtworkId, $this->ArtworkTags[$i]->TagId]);
			}
		}
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
}
