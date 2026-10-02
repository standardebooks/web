<?
use Safe\DateTimeImmutable;

class Artist{
	use Traits\PropertyFromRequest;

	public int $ArtistId;
	public string $Name = '';
	public DateTimeImmutable $CreatedAt;
	public DateTimeImmutable $UpdatedAt;
	public ?int $DeathYear = null;

	public string $UrlName{
		get{
			if(!isset($this->UrlName)){
				if($this->Name == ''){
					$this->UrlName = '';
				}
				else{
					$this->UrlName = Formatter::MakeUrlSafe($this->Name);
				}
			}

			return $this->UrlName;
		}
	}

	public string $Url{
		get{
			return '/artworks/' . $this->UrlName;
		}
	}

	public string $DeleteUrl{
		get{
			return '/artworks/' . $this->UrlName . '/delete';
		}
	}

	/** @var array<string> $AlternateNames */
	public private(set) array $AlternateNames{
		get{
			if(!isset($this->AlternateNames)){
				$this->AlternateNames = [];

				if(isset($this->ArtistId)){
					$result = Db::Query('
						select *
						from ArtistAlternateNames
						where ArtistId = ?
					', [$this->ArtistId]);

					foreach($result as $row){
						$this->AlternateNames[] = $row->Name;
					}
				}
			}

			return $this->AlternateNames;
		}
	}

	public string $AlternateNamesString{
		get{
			$alternateNamesString = '';

			$alternateNames = array_slice($this->AlternateNames, 0, -2);
			$lastTwoAlternateNames = array_slice($this->AlternateNames, -2);

			foreach($alternateNames as $alternateName){
				$alternateNamesString .= $alternateName . ', ';
			}

			$alternateNamesString = rtrim($alternateNamesString, ', ');

			if(sizeof($lastTwoAlternateNames) == 1){
				if(sizeof($alternateNames) > 0){
					$alternateNamesString .= ', and ';
				}

				$alternateNamesString .= $lastTwoAlternateNames[0];
			}

			if(sizeof($lastTwoAlternateNames) == 2){
				if(sizeof($alternateNames) > 0){
					$alternateNamesString .= ', ';
					$alternateNamesString .= $lastTwoAlternateNames[0] . ', and ' . $lastTwoAlternateNames[1];
				}
				else{
					$alternateNamesString .= $lastTwoAlternateNames[0] . ' and ' . $lastTwoAlternateNames[1];
				}
			}

			return $alternateNamesString;
		}
	}


	// *******
	// METHODS
	// *******

	/**
	 * @throws Exceptions\ArtistInvalidException
	 */
	public function Validate(): void{
		$thisYear = intval(NOW->format('Y'));

		$error = new Exceptions\ArtistInvalidException();

		$this->Name = trim($this->Name);

		if($this->Name == ''){
			$error->Add(new Exceptions\ArtistNameRequiredException());
		}
		elseif(mb_strlen($this->Name, 'utf-8') > ARTWORK_MAX_STRING_LENGTH){
			$error->Add(new Exceptions\StringTooLongException('Artist Name'));
		}

		if($this->Name == 'Anonymous' && $this->DeathYear !== null){
			$this->DeathYear = null;
		}

		if($this->DeathYear !== null && ($this->DeathYear <= 0 || $this->DeathYear > $thisYear + 50)){
			$error->Add(new Exceptions\DeathYearInvalidException());
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	public function FillFromRequestBody(): void{
		$name = $this->Name;

		$this->PropertyFromRequest('Name');
		$this->PropertyFromRequest('DeathYear');

		if($this->Name != $name){
			$this->UrlName = $this->Name == '' ? '' : Formatter::MakeUrlSafe($this->Name);
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

		return Db::Query('
				select *
				from Artists
				where ArtistId = ?
			', [$artistId], Artist::class)[0] ?? throw new Exceptions\ArtistNotFoundException();
	}

	/**
	 * @return array<Artist>
	 */
	public static function GetAll(): array{
		return Db::Query('
			select *
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
				select a.*
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
				select *
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
				select a.*
					from Artists a
					left outer join ArtistAlternateNames aan using (ArtistId)
					where aan.UrlName = ?
					limit 1
			', [$urlName], Artist::class)[0] ?? throw new Exceptions\ArtistNotFoundException();
	}

	/**
	 * @throws Exceptions\ArtistAlternateNameExistsException
	 * @throws Exceptions\ArtistNotFoundException If an `Artwork`'s `Artist` can't be found while updating search data.
	 */
	public function AddAlternateName(string $name): void{
		try{
			Db::Query('
				insert into ArtistAlternateNames (ArtistId, Name, UrlName)
				values (?,
					?,
					?)
			', [$this->ArtistId, $name, Formatter::MakeUrlSafe($name)]);

			$this->UpdateSearchRepresentation();
		}
		catch(Exceptions\DuplicateDatabaseKeyException){
			throw new Exceptions\ArtistAlternateNameExistsException();
		}
	}

	/**
	 * Reassigns all the artworks currently assigned to this artist to the given canoncial artist.
	 *
	 * @param Artist $canonicalArtist
	 *
	 * @throws Exceptions\ArtistNotFoundException If an `Artwork`'s `Artist` can't be found while updating search data.
	 */
	public function ReassignArtworkTo(Artist $canonicalArtist): void{
		Db::Query('
			update Artworks
			set ArtistId = ?
			where ArtistId = ?
		', [$canonicalArtist->ArtistId, $this->ArtistId]);

		Db::Query('
			update
			ArtistAlternateNames
			set ArtistId = ?
			where ArtistId = ?
		', [$canonicalArtist->ArtistId, $this->ArtistId]);

		$canonicalArtist->UpdateSearchRepresentation();
	}

	/**
	 * @throws Exceptions\ArtistInvalidException
	 */
	public function Create(): void{
		$this->Validate();
		$this->ArtistId = Db::QueryInt('
			insert into Artists (Name, UrlName, DeathYear)
			values (?,
			        ?,
			        ?)
			returning ArtistId
		', [$this->Name, $this->UrlName, $this->DeathYear]);
	}

	/**
	 * Update the search database for this `Artist`.
	 *
	 * @throws Exceptions\ArtistNotFoundException If an `Artwork`'s `Artist` can't be found.
	 */
	public function UpdateSearchRepresentation(): void{
		$artworks = Db::Query('select * from Artworks where ArtistId = ?', [$this->ArtistId], Artwork::class);
		foreach($artworks as $artwork){
			$artwork->UpdateSearchRepresentation();
		}
	}

	/**
	 * @throws Exceptions\ArtistInvalidException
	 */
	public static function GetOrCreate(Artist $artist): Artist{
		$result = Db::Query('
					select a.*
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
			select exists (
				select ArtworkId
				from Artworks
				where ArtistId = ?
			)', [$this->ArtistId]);

		if($hasArtwork){
			throw new Exceptions\ArtistHasArtworkException();
		}

		Db::Query('
			delete
			from Artists
			where ArtistId = ?
		', [$this->ArtistId]);

		Db::Query('
			delete
			from ArtistAlternateNames
			where ArtistId = ?
		', [$this->ArtistId]);
	}
}
