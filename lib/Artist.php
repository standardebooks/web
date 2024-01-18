<?
use Safe\DateTime;
use function Safe\date;

/**
 * @property string $UrlName
 * @property array<string> $AlternateNames
 * @property array<string> $_AlternateNames
 */
class Artist extends PropertiesBase{
	public ?int $ArtistId = null;
	public ?string $Name = null;
	public ?int $DeathYear = null;
	public ?datetime $Created = null;
	public ?datetime $Updated = null;
	protected ?string $_UrlName = null;
	protected $_AlternateNames;

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

	/**
	 * @return array<string>
	 */
	protected function GetAlternateNames(): array{
		if($this->_AlternateNames === null){
			$this->_AlternateNames = [];

			$result = Db::Query('
					SELECT *
					from ArtistAlternateNames
					where ArtistId = ?
				', [$this->ArtistId]);

			foreach($result as $row){
				$this->_AlternateNames[] = $row->Name;
			}
		}

		return $this->_AlternateNames;
	}

	// *******
	// METHODS
	// *******

	public function Validate(): void{
		$now = new DateTime('now', new DateTimeZone('UTC'));
		$thisYear = intval($now->format('Y'));

		$error = new Exceptions\ValidationException();

		if($this->Name === null || $this->Name == ''){
			$error->Add(new Exceptions\ArtistNameRequiredException());
		}

		if($this->Name !== null && strlen($this->Name) > ARTWORK_MAX_STRING_LENGTH){
			$error->Add(new Exceptions\StringTooLongException('Artist Name'));
		}

		if($this->DeathYear !== null && ($this->DeathYear <= 0 || $this->DeathYear > $thisYear + 50)){
			$error->Add(new Exceptions\InvalidDeathYearException());
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
			values (?,
			        ?,
			        ?)
		', [$this->Name, $this->UrlName, $this->DeathYear]);

		$this->ArtistId = Db::GetLastInsertedId();
	}

	/**
	 * @throws \Exceptions\ValidationException
	 */
	public static function GetOrCreate(Artist $artist): Artist{
		$result = Db::Query('
					SELECT a.*
					from Artists a
					left outer join ArtistAlternateNames aan using (ArtistId)
					where a.UrlName = ?
					    or aan.UrlName = ?
					limit 1
		', [$artist->UrlName, $artist->UrlName], 'Artist');

		if(isset($result[0])){
			return $result[0];
		}
		else{
			$artist->Create();
			return $artist;
		}
	}

	public function Delete(): void{
		Db::Query('
			DELETE
			from Artists
			where ArtistId = ?
		', [$this->ArtistId]);
		Db::Query('
			DELETE
			from ArtistAlternateNames
			where ArtistId = ?
		', [$this->ArtistId]);
	}
}
