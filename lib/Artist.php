<?
use Safe\DateTimeImmutable;

/**
 * @property ?int $DeathYear
 * @property ?string $UrlName
 * @property ?string $Url
 * @property ?array<string> $AlternateNames
 */
class Artist{
	use Traits\Accessor;

	public ?int $ArtistId = null;
	public ?string $Name = null;
	public ?DateTimeImmutable $Created = null;
	public ?DateTimeImmutable $Updated = null;

	protected ?int $_DeathYear = null;
	protected ?string $_UrlName = null;
	protected ?string $_Url = null;
	/** @var ?array<string> $_AlternateNames */
	protected $_AlternateNames = null;

	// *******
	// SETTERS
	// *******

	protected function SetDeathYear(?int $deathYear): void{
		if($this->Name == 'Anonymous'){
			$this->_DeathYear = null;
		}
		else {
			$this->_DeathYear = $deathYear;
		}
	}

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

	protected function GetUrl(): string{
		if($this->_Url === null){
			$this->_Url = '/artworks/' . $this->UrlName;
		}

		return $this->_Url;
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

	/**
	 * @throws Exceptions\InvalidArtistException
	 */
	public function Validate(): void{
		/** @throws void */
		$now = new DateTimeImmutable();
		$thisYear = intval($now->format('Y'));

		$error = new Exceptions\InvalidArtistException();

		if($this->Name === null || $this->Name == ''){
			$error->Add(new Exceptions\ArtistNameRequiredException());
		}

		if($this->Name !== null && strlen($this->Name) > ARTWORK_MAX_STRING_LENGTH){
			$error->Add(new Exceptions\StringTooLongException('Artist Name'));
		}

		if($this->Name == 'Anonymous' && $this->DeathYear !== null){
			$this->_DeathYear = null;
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


	/**
	 * @throws Exceptions\ArtistNotFoundException
	 */
	public static function Get(?int $artistId): Artist{
		if($artistId === null){
			throw new Exceptions\ArtistNotFoundException();
		}

		$result = Db::Query('
				SELECT *
				from Artists
				where ArtistId = ?
			', [$artistId], Artist::class);

		return $result[0] ?? throw new Exceptions\ArtistNotFoundException();;
	}

	/**
	 * @throws Exceptions\InvalidArtistException
	 */
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
	 * @throws Exceptions\InvalidArtistException
	 */
	public static function GetOrCreate(Artist $artist): Artist{
		$result = Db::Query('
					SELECT a.*
					from Artists a
					left outer join ArtistAlternateNames aan using (ArtistId)
					where a.UrlName = ?
					    or aan.UrlName = ?
					limit 1
		', [$artist->UrlName, $artist->UrlName], Artist::class);

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
