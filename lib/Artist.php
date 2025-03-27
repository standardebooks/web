<?
use Safe\DateTimeImmutable;

/**
 * @property ?int $DeathYear
 * @property string $UrlName
 * @property-read string $Url
 * @property-read string $DeleteUrl
 * @property array<string> $AlternateNames
 * @property-read string $AlternateNamesString
 */
class Artist{
	use Traits\Accessor;
	use Traits\PropertyFromHttp;

	public int $ArtistId;
	public string $Name = '';
	public DateTimeImmutable $Created;
	public DateTimeImmutable $Updated;
	public ?int $DeathYear = null;

	protected string $_UrlName;
	protected string $_Url;
	protected string $_DeleteUrl;
	/** @var array<string> $_AlternateNames */
	protected array $_AlternateNames;
	protected string $_AlternateNamesString;


	// *******
	// GETTERS
	// *******

	protected function GetUrlName(): string{
		if(!isset($this->_UrlName)){
			if(!isset($this->Name) || $this->Name == ''){
				$this->_UrlName = '';
			}
			else{
				$this->_UrlName = Formatter::MakeUrlSafe($this->Name);
			}
		}

		return $this->_UrlName;
	}

	protected function GetUrl(): string{
		return $this->_Url ??= '/artworks/' . $this->UrlName;
	}

	protected function GetDeleteUrl(): string{
		return $this->_DeleteUrl ??= '/artworks/' . $this->UrlName . '/delete';
	}

	/**
	 * @return array<string>
	 */
	protected function GetAlternateNames(): array{
		if(!isset($this->_AlternateNames)){
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

	protected function GetAlternateNamesString(): string{
		if(!isset($this->_AlternateNamesString)){
			$this->_AlternateNamesString = '';

			$alternateNames = array_slice($this->AlternateNames, 0, -2);
			$lastTwoAlternateNames = array_slice($this->AlternateNames, -2);

			foreach($alternateNames as $alternateName){
				$this->_AlternateNamesString .= $alternateName . ', ';
			}

			$this->_AlternateNamesString = rtrim($this->_AlternateNamesString, ', ');

			if(sizeof($lastTwoAlternateNames) == 1){
				if(sizeof($alternateNames) > 0){
					$this->_AlternateNamesString .= ', and ';
				}

				$this->_AlternateNamesString .= $lastTwoAlternateNames[0];
			}

			if(sizeof($lastTwoAlternateNames) == 2){
				if(sizeof($alternateNames) > 0){
					$this->_AlternateNamesString .= ', ';
					$this->_AlternateNamesString .= $lastTwoAlternateNames[0] . ', and ' . $lastTwoAlternateNames[1];
				}
				else{
					$this->_AlternateNamesString .= $lastTwoAlternateNames[0] . ' and ' . $lastTwoAlternateNames[1];
				}
			}
		}

		return $this->_AlternateNamesString;
	}


	// *******
	// METHODS
	// *******

	/**
	 * @throws Exceptions\InvalidArtistException
	 */
	public function Validate(): void{
		$thisYear = intval(NOW->format('Y'));

		$error = new Exceptions\InvalidArtistException();

		$this->Name = trim($this->Name ?? '');

		if($this->Name == ''){
			$error->Add(new Exceptions\ArtistNameRequiredException());
		}
		elseif(strlen($this->Name) > ARTWORK_MAX_STRING_LENGTH){
			$error->Add(new Exceptions\StringTooLongException('Artist Name'));
		}

		if($this->Name == 'Anonymous' && $this->DeathYear !== null){
			$this->DeathYear = null;
		}

		if($this->DeathYear !== null && ($this->DeathYear <= 0 || $this->DeathYear > $thisYear + 50)){
			$error->Add(new Exceptions\InvalidDeathYearException());
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	public function FillFromHttpPost(): void{
		$this->PropertyFromHttp('Name');
		$this->PropertyFromHttp('DeathYear');
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

		return Db::Query('
				SELECT *
				from Artists
				where ArtistId = ?
			', [$artistId], Artist::class)[0] ?? throw new Exceptions\ArtistNotFoundException();
	}

	/**
	 * @return array<Artist>
	 */
	public static function GetAll(): array{
		return Db::Query('
			SELECT *
			from Artists
			order by Name asc', [], Artist::class);
	}

	/**
	 * @throws Exceptions\ArtistNotFoundException
	 */
	public static function GetByName(?string $name): Artist{
		if($name === null){
			throw new Exceptions\ArtistNotFoundException();
		}

		return Db::Query('
				SELECT a.*
					from Artists a
					left outer join ArtistAlternateNames aan using (ArtistId)
					where a.Name = ?
					    or aan.Name = ?
			', [$name, $name], Artist::class)[0] ?? throw new Exceptions\ArtistNotFoundException();
	}

	/**
	 * @throws Exceptions\ArtistNotFoundException
	 */
	public static function GetByUrlName(?string $urlName): Artist{
		if($urlName === null){
			throw new Exceptions\ArtistNotFoundException();
		}

		return Db::Query('
				SELECT *
					from Artists
					where UrlName = ?
			', [$urlName], Artist::class)[0] ?? throw new Exceptions\ArtistNotFoundException();
	}

	/**
	 * @throws Exceptions\ArtistNotFoundException
	 */
	public static function GetByAlternateUrlName(?string $urlName): Artist{
		if($urlName === null){
			throw new Exceptions\ArtistNotFoundException();
		}

		return Db::Query('
				SELECT a.*
					from Artists a
					left outer join ArtistAlternateNames aan using (ArtistId)
					where aan.UrlName = ?
					limit 1
			', [$urlName], Artist::class)[0] ?? throw new Exceptions\ArtistNotFoundException();
	}

	/**
	 * @throws Exceptions\ArtistAlternateNameExistsException
	 */
	public function AddAlternateName(string $name): void{
		try{
			Db::Query('
				INSERT into ArtistAlternateNames (ArtistId, Name, UrlName)
				values (?,
					?,
					?)
			', [$this->ArtistId, $name, Formatter::MakeUrlSafe($name)]);
		}
		catch(Exceptions\DuplicateDatabaseKeyException){
			throw new Exceptions\ArtistAlternateNameExistsException();
		}
	}

	/**
	 * Reassigns all the artworks currently assigned to this artist to the given canoncial artist.
	 *
	 * @param Artist $canonicalArtist
	 */
	public function ReassignArtworkTo(Artist $canonicalArtist): void{
		Db::Query('
			UPDATE Artworks
			set ArtistId = ?
			where ArtistId = ?
		', [$canonicalArtist->ArtistId, $this->ArtistId]);

		Db::Query('
			UPDATE
			ArtistAlternateNames
			set ArtistId = ?
			where ArtistId = ?
		', [$canonicalArtist->ArtistId, $this->ArtistId]);
	}

	/**
	 * @throws Exceptions\InvalidArtistException
	 */
	public function Create(): void{
		$this->Validate();
		$this->ArtistId = Db::QueryInt('
			INSERT into Artists (Name, UrlName, DeathYear)
			values (?,
			        ?,
			        ?)
			returning ArtistId
		', [$this->Name, $this->UrlName, $this->DeathYear]);
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

	/**
	 * @throws Exceptions\ArtistHasArtworkException
	 */
	public function Delete(): void{
		$hasArtwork = Db::QueryBool('
			SELECT exists (
				SELECT ArtworkId
				from Artworks
				where ArtistId = ?
			)', [$this->ArtistId]);

		if($hasArtwork){
			throw new Exceptions\ArtistHasArtworkException();
		}

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
