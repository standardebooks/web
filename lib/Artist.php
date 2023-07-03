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
			INSERT into Artists (Name, DeathYear)
			values (?,
			        ?)
		', [$this->Name, $this->DeathYear]);

		$this->ArtistId = Db::GetLastInsertedId();
	}

	public function Delete(): void{
		Db::Query('
			DELETE
			from Artists
			where ArtistId = ?
		', [$this->ArtistId]);
	}

	public static function GetOrCreate(string $name, ?int $deathYear): Artist{
		$result = Db::Query('
            SELECT *
            FROM Artists
            WHERE Name = ? AND DeathYear = ?', [$name, $deathYear], 'Artist');

		if (isset($result[0])){
			return $result[0];
		}

		$artist = new Artist();
		$artist->Name = $name;
		$artist->DeathYear = $deathYear;
		$artist->Create();

		return $artist;
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
