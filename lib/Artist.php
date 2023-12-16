<?
use Safe\DateTime;
use function Safe\date;

/**
 * @property string $UrlName
 * @property array<string> $AlternateSpellings
 */
class Artist extends PropertiesBase{
	public $ArtistId;
	public $Name;
	public $DeathYear;
	protected $_UrlName;
	protected $_AlternateSpellings;

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
	 * @return array<string>
	 */
	protected function GetAlternateSpellings(): array{
		if($this->_AlternateSpellings === null){
			$this->_AlternateSpellings = array();

			$result = Db::Query('
					SELECT *
					from ArtistAlternateSpellings
					where ArtistId = ?
				', [$this->ArtistId], 'stdClass');

			foreach($result as $row){
				$this->_AlternateSpellings[] = $row->AlternateSpelling;
			}
		}

		return $this->_AlternateSpellings;
	}

	// *******
	// METHODS
	// *******

	public function Validate(): void{
		$error = new Exceptions\ValidationException();

		if($this->Name === null || $this->Name == ''){
			$error->Add(new Exceptions\ArtistNameRequiredException());
		}

		if($this->Name !== null && strlen($this->Name) > COVER_ARTWORK_MAX_STRING_LENGTH){
			$error->Add(new Exceptions\StringTooLongException('Artist Name'));
		}

		if($this->DeathYear !== null && ($this->DeathYear <=0 || $this->DeathYear > intval(date('Y')))){
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
			VALUES (?,
			        ?,
			        ?)
		', [$this->Name, $this->UrlName, $this->DeathYear]);

		$this->ArtistId = Db::GetLastInsertedId();
	}

	/**
	 * @throws \Exceptions\ValidationException
	 */
	public function GetOrCreate(): void{
		$this->Validate();
		$result = Db::Query('
			SELECT *
			FROM Artists
			WHERE UrlName = ?
		', [$this->UrlName], 'Artist');

		if(isset($result[0])){
			$this->ArtistId = $result[0]->ArtistId;
			return;
		}

		$this->Create();
	}

	public static function FindMatch(string $artistName): ?Artist{
		$result = Db::Query('
			SELECT a.*
			FROM Artists a LEFT JOIN ArtistAlternateSpellings alt USING (ArtistId)
			WHERE a.Name = ? OR alt.AlternateSpelling = ?
			ORDER BY a.DeathYear DESC
			LIMIT 1;
		', [$artistName, $artistName], 'Artist');

		return $result[0] ?? null;
	}

	public function Delete(): void{
		Db::Query('
			DELETE
			from Artists
			where ArtistId = ?
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
