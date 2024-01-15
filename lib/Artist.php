<?
use Safe\DateTime;
use function Safe\date;

/**
 * @property string $UrlName
 * @property array<string> $AlternateSpellings
 * @property array<string> $_AlternateSpellings
 */
class Artist extends PropertiesBase{
	public ?int $ArtistId = null;
	public ?string $Name = null;
	public ?int $DeathYear = null;
	public ?datetime $Created = null;
	public ?datetime $Updated = null;
	protected ?string $_UrlName = null;
	protected $_AlternateSpellings;

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
	protected function GetAlternateSpellings(): array{
		if($this->_AlternateSpellings === null){
			$this->_AlternateSpellings = [];

			$result = Db::Query('
					SELECT *
					from ArtistAlternateSpellings
					where ArtistId = ?
				', [$this->ArtistId]);

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

		if($this->Name !== null && strlen($this->Name) > ARTWORK_MAX_STRING_LENGTH){
			$error->Add(new Exceptions\StringTooLongException('Artist Name'));
		}

		if($this->DeathYear !== null && ($this->DeathYear <= 0 || $this->DeathYear > intval(date('Y')) + 50)){
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
			SELECT *
			from Artists
			where UrlName = ?
		', [$artist->UrlName], 'Artist');

		if(isset($result[0])){
			return $result[0];
		}
		else{
			$artist->Create();
			return $artist;
		}
	}

	public static function FindMatch(string $artistName): ?Artist{
		$result = Db::Query('
			SELECT a.*
			from Artists a left join ArtistAlternateSpellings alt using (ArtistId)
			where a.Name = ? or alt.AlternateSpelling = ?
			order by a.DeathYear desc
			limit 1;
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
}
