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
	public $ArtistId;
	public $CompletedYear;
	public $ImageFilesystemPath;
	public $Created;
	public $Status;
	protected $_UrlName;
	protected $_ArtworkTags = null;
	protected $_Artist = null;

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

		if($this->CompletedYear === null || strlen($this->CompletedYear) === 0){
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

		if($error->HasExceptions){
			throw $error;
		}
	}

	public function Create(): void{
		$this->Validate();
		$this->Created = new DateTime();
		Db::Query('
			INSERT into Artworks (ArtistId, Name, UrlName, CompletedYear, ImageFilesystemPath, Created)
			values (?,
			        ?,
			        ?,
			        ?,
			        ?,
			        ?)
		', [$this->Artist->ArtistId, $this->Name, $this->UrlName, $this->CompletedYear, $this->ImageFilesystemPath, $this->Created]);

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
