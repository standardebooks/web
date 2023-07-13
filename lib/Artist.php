<?
use Safe\DateTime;

/**
 * @property string $UrlName
 */
class Artist extends PropertiesBase{
	public $ArtistId;
	public $Name;
	public $DeathYear;
	protected $_UrlName;

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

	// *******
	// METHODS
	// *******

	protected function Validate(): void{
		$error = new Exceptions\ValidationException();

		if($this->Name === null || strlen($this->Name) === 0){
			$error->Add(new Exceptions\InvalidArtistException());
		}

		if($this->UrlName === null || strlen($this->UrlName) === 0){
			$error->Add(new Exceptions\InvalidArtistException());
		}

		if($error->HasExceptions){
			throw $error;
		}
	}
	// ***********
	// ORM METHODS
	// ***********

	public static function Get(?int $artistId): Artist{
		if($artistId === null){
			throw new Exceptions\InvalidArtistException();
		}

		$result = Db::Query('
				SELECT *
				from Artists
				where ArtistId = ?
			', [$artistId], 'Artist');

		if(sizeof($result) == 0){
			throw new Exceptions\InvalidArtistException();
		}

		return $result[0];
	}

	public function Create(): void{
		$this->Validate();
		Db::Query('
			INSERT into Artists (Name, UrlName, DeathYear)
			VALUES (?,
			        ?,
			        ?)
		', [$this->Name, $this->UrlName, $this->DeathYear]);

		$this->ArtistId = Db::GetLastInsertedId();
	}

	public function GetOrCreate(): void{
		$this->Validate();
		$result = Db::Query('
			SELECT *
			FROM Artists
			WHERE Name = ? AND DeathYear = ?
		', [$this->Name, $this->DeathYear], 'Artist');

		if (isset($result[0])){
			$this->ArtistId = $result[0]->ArtistId;
			return;
		}

		$this->Create();
	}

	public function Delete(): void{
		Db::Query('
			DELETE
			from Artists
			where ArtistId = ?
		', [$this->ArtistId]);
	}

	public function DeleteIfUnused(): void{
		Db::Query('
			DELETE
			FROM Artists
			WHERE ArtistId NOT IN (SELECT ArtistId FROM Artworks)
			  AND ArtistId = ?
		', [$this->ArtistId]);
	}

	/**
	 * @return array<Artist>
	 */
	public static function GetAll(): array{
		return Db::Query('
			SELECT *
			FROM Artists
			ORDER BY Name', [], 'Artist');
	}
}
