<?
use function Safe\preg_match;

/** A contributor's shared metadata and, when loaded for an ebook, their credit details. */
class Contributor{
	public int $ContributorId;
	public int $EbookId;
	public string $Name;
	public string $UrlName;
	public ?string $SortName = null;
	public ?string $WikipediaUrl = null;
	public Enums\MarcRole $MarcRole;
	public ?string $FullName = null;
	public ?string $MetadataXmlFragment = null;
	public ?string $NacoafUrl = null;
	public int $SortOrder;
	public bool $IsDisplayed = true;
	public ?int $DeathYear = null;
	public bool $IsDeathYearCirca = false;

	/** The contributor's URL when their MARC role is author. */
	public ?string $Url{
		get{
			if($this->MarcRole == Enums\MarcRole::Author){
				return '/ebooks/' . $this->UrlName;
			}

			return null;
		}
	}

	/** The contributor's name, with a lifespan when the URL name includes one. */
	public ?string $DisplayName{
		get{
			$displayName = $this->Name;
			// Append a lifespan when it is encoded in the URL name to distinguish authors with identical names.
			if(preg_match('|-(\d{4})-(\d{4})$|u', $this->UrlName, $matches)){
				$displayName .= ' (' . $matches[1] . '–' . $matches[2] . ')';
			}

			return $displayName;
		}
	}

	// *******
	// METHODS
	// *******

	/**
	 * Get contributors credited with the given role, sorted by name.
	 *
	 * @return array<Contributor>
	 */
	public static function GetAllByMarcRole(Enums\MarcRole $marcRole): array{
		return Db::Query('
			select
			*
			from Contributors
			inner join EbookContributors using (ContributorId)
			where MarcRole = ?
			and SortName is not null
			group by UrlName
			# Replace spaces in `SortName` because MariaDB sorts names like `Thomas a Kempis` before everything else, instead of sorting it like `ak`.
			order by replace(SortName, " " , "") asc', [$marcRole], Contributor::class);
	}

	/**
	 * Get distinct names credited with the given role.
	 *
	 * @return array<stdClass>
	 */
	public static function GetAllNamesByMarcRole(Enums\MarcRole $marcRole): array{
		return Db::Query('
			select
			distinct Name
			from Contributors
			inner join EbookContributors using (ContributorId)
			where MarcRole = ?
			order by Name asc', [$marcRole]);
	}

	/**
	 * Normalize and validate the contributor's metadata and ebook credit.
	 *
	 * @throws Exceptions\ContributorInvalidException If the contributor's metadata or credit is invalid.
	 */
	public function Validate(): void{
		$error = new Exceptions\ContributorInvalidException();

		if(!isset($this->EbookId)){
			$error->Add(new Exceptions\ContributorEbookIdRequiredException());
		}

		$this->Name = trim($this->Name ?? '');
		if($this->Name == ''){
			$error->Add(new Exceptions\ContributorNameRequiredException());
		}
		else{
			// Sometimes placeholders may have `'` in the name.
			$this->Name = str_replace('\'', '’', $this->Name);
		}

		if(isset($this->UrlName)){
			$this->UrlName = trim($this->UrlName);

			if($this->UrlName == ''){
				$error->Add(new Exceptions\ContributorUrlNameRequiredException());
			}
		}
		else{
			$error->Add(new Exceptions\ContributorUrlNameRequiredException());
		}

		$this->SortName = trim($this->SortName ?? '');
		if($this->SortName == ''){
			$this->SortName = null;
		}

		$this->FullName = trim($this->FullName ?? '');
		if($this->FullName == ''){
			$this->FullName = null;
		}

		$this->WikipediaUrl = trim($this->WikipediaUrl ?? '');
		if($this->WikipediaUrl == ''){
			$this->WikipediaUrl = null;
		}

		if(isset($this->WikipediaUrl)){
			if(!preg_match('|^https://.*wiki.*|ius', $this->WikipediaUrl)){
				$error->Add(new Exceptions\ContributorWikipediaUrlInvalidException('Invalid Contributor WikipediaUrl: ' . $this->WikipediaUrl));
			}

			if(strlen($this->WikipediaUrl) > EBOOKS_MAX_STRING_LENGTH){
				$error->Add(new Exceptions\StringTooLongException('Contributor WikipediaUrl'));
			}
		}

		$this->NacoafUrl = trim($this->NacoafUrl ?? '');
		if($this->NacoafUrl == ''){
			$this->NacoafUrl = null;
		}

		if(isset($this->NacoafUrl)){
			if(!preg_match('|^https?://id\.loc\.gov/.*|ius', $this->NacoafUrl)){
				$error->Add(new Exceptions\ContributorNacoafUrlInvalidException('Invalid Contributor NacoafUrl: ' . $this->NacoafUrl));
			}

			if(strlen($this->NacoafUrl) > EBOOKS_MAX_STRING_LENGTH){
				$error->Add(new Exceptions\StringTooLongException('Contributor NacoafUrl'));
			}
		}

		if(!isset($this->SortOrder)){
			$error->Add(new Exceptions\ContributorSortOrderRequiredException());
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	/**
	 * Create or update the contributor's shared metadata and add their ebook credit.
	 *
	 * @throws Exceptions\ContributorInvalidException If the contributor's metadata or credit is invalid.
	 * @throws Exceptions\ContributorExistsException If another contributor already has the same NACOAF URL.
	 */
	public function Create(bool $copyFromExistingName): void{
		if($copyFromExistingName){
			try{
				// This is useful when adding new placeholders for authors who already exist with detailed metadata.
				$existingContributor = Contributor::GetByUrlName($this->UrlName);
				$this->SortName = $existingContributor->SortName;
				$this->WikipediaUrl = $existingContributor->WikipediaUrl;
				$this->FullName = $existingContributor->FullName;
				$this->MetadataXmlFragment = $existingContributor->MetadataXmlFragment;
				$this->NacoafUrl = $existingContributor->NacoafUrl;
				$this->DeathYear = $existingContributor->DeathYear;
				$this->IsDeathYearCirca = $existingContributor->IsDeathYearCirca;
			}
			catch(Exceptions\ContributorNotFoundException){
				// Not found, pass.
			}
		}

		$this->Validate();
		Db::Transaction(function(): void{
			// Match the strongest available identity without merging distinct people who share a name.
			$condition = 'NacoafUrl is null and WikipediaUrl is null and Name = ?';
			$identity = $this->Name;
			if($this->NacoafUrl !== null){
				$condition = 'NacoafUrl = ?';
				$identity = $this->NacoafUrl;
			}
			elseif($this->WikipediaUrl !== null){
				$condition = 'NacoafUrl is null and WikipediaUrl = ?';
				$identity = $this->WikipediaUrl;
			}

			$contributorId = Db::QueryInt('select ContributorId from Contributors where ' . $condition . ' order by ContributorId limit 1 for update', [$identity]);
			$parameters = [$this->Name, $this->UrlName, $this->SortName, $this->WikipediaUrl, $this->FullName, $this->MetadataXmlFragment, $this->NacoafUrl];
			try{
				if($contributorId == 0){
					$contributorId = Db::QueryInt('insert into Contributors (Name, UrlName, SortName, WikipediaUrl, FullName, MetadataXmlFragment, NacoafUrl) values (?, ?, ?, ?, ?, ?, ?) returning ContributorId', $parameters);
				}
				else{
					$parameters[] = $contributorId;
					Db::Query('update Contributors set Name = ?, UrlName = ?, SortName = ?, WikipediaUrl = ?, FullName = ?, MetadataXmlFragment = ?, NacoafUrl = ? where ContributorId = ?', $parameters);
				}
			}
			catch(Exceptions\DuplicateDatabaseKeyException $ex){
				throw new Exceptions\ContributorExistsException('A contributor already exists with NACOAF URL: ' . $this->NacoafUrl, 0, $ex);
			}

			$this->ContributorId = $contributorId;
			Db::Query('insert into EbookContributors (EbookId, ContributorId, MarcRole, SortOrder, IsDisplayed) values (?, ?, ?, ?, ?)', [$this->EbookId, $this->ContributorId, $this->MarcRole, $this->SortOrder, $this->IsDisplayed]);
		});
	}

	/**
	 * Given a list of `Contributor`s, generate a string like `Leo Tolstoy, Ford Madox Ford, and Joseph Conrad`.
	 *
	 * @param array<Contributor> $contributors
	 * @param bool $includeHtml **`TRUE`** to include inline HTML for Wikipedia links, etc.
	 * @param bool $includeRdfa **`TRUE`** to include RDFa schemas if `$includeHtml` is also **`TRUE`**.
	 */
	public static function GenerateContributorsString(array $contributors, bool $includeHtml, bool $includeRdfa): string{
		$string = '';
		$i = 0;

		foreach($contributors as $contributor){
			$role = 'schema:contributor';
			switch($contributor->MarcRole){
				case Enums\MarcRole::Translator:
					$role = 'schema:translator';
					break;
				case Enums\MarcRole::Illustrator:
					$role = 'schema:illustrator';
					break;
				case Enums\MarcRole::Editor:
					$role = 'schema:editor';
					break;
			}

			if(!$includeHtml){
				$string .= $contributor->Name;
			}
			else{
				if($contributor->WikipediaUrl){
					if($includeRdfa){
						$string .= '<a property="' . $role . '" typeof="schema:Person" href="' . Formatter::EscapeHtml($contributor->WikipediaUrl) .'"><span property="schema:name">' . Formatter::EscapeHtml($contributor->Name) . '</span>';

						if($contributor->NacoafUrl){
							$string .= '<meta property="schema:sameAs" content="' . Formatter::EscapeHtml($contributor->NacoafUrl) . '"/>';
						}
					}
					else{
						$string .= '<a href="' . Formatter::EscapeHtml($contributor->WikipediaUrl) .'">' . Formatter::EscapeHtml($contributor->Name);
					}

					$string .= '</a>';
				}
				else{
					if($includeRdfa){
						$string .= '<span property="' . $role . '" typeof="schema:Person"><span property="schema:name">' . Formatter::EscapeHtml($contributor->Name) . '</span>';

						if($contributor->NacoafUrl){
							$string .= '<meta property="schema:sameAs" content="' . Formatter::EscapeHtml($contributor->NacoafUrl) . '"/>';
						}

						$string .= '</span>';
					}
					else{
						$string .= Formatter::EscapeHtml($contributor->Name);
					}
				}
			}

			if($i == sizeof($contributors) - 2 && sizeof($contributors) > 2){
				$string .= ', and ';
			}
			elseif($i == sizeof($contributors) - 2){
				$string .= ' and ';
			}
			elseif($i != sizeof($contributors) - 1){
				$string .= ', ';
			}

			$i++;
		}

		return $string;
	}

	// ***********
	// ORM METHODS
	// ***********

	/**
	 * Find a contributor by internal ID, NACOAF URL, Wikipedia URL, or name, in that order.
	 *
	 * @throws Exceptions\ContributorNotFoundException If no contributor matches the identifier.
	 * @throws Exceptions\AmbiguousContributorException If multiple contributors match the identifier.
	 */
	public static function GetByIdentifier(?string $identifier): Contributor{
		if($identifier === null || $identifier === ''){
			throw new Exceptions\ContributorNotFoundException();
		}

		$columns = ['NacoafUrl', 'WikipediaUrl', 'Name'];
		if(ctype_digit($identifier)){
			array_unshift($columns, 'ContributorId');
		}

		foreach($columns as $column){
			$contributors = Db::Query('select * from Contributors where ' . $column . ' = ? order by ContributorId', [$identifier], Contributor::class);
			if(sizeof($contributors) > 1){
				throw new Exceptions\AmbiguousContributorException($contributors);
			}
			if(sizeof($contributors) === 1){
				return $contributors[0];
			}
		}

		throw new Exceptions\ContributorNotFoundException();
	}

	/**
	 * Get distinct contributors credited with the given role.
	 *
	 * @return array<Contributor>
	 */
	public static function GetDistinctByMarcRole(Enums\MarcRole $marcRole): array{
		return Db::Query('select * from Contributors inner join EbookContributors using (ContributorId) where MarcRole = ? group by UrlName', [$marcRole], Contributor::class);
	}

	/**
	 * Get contributors matching the URL names and credited with the given role.
	 *
	 * @param array<string> $urlNames
	 * @param Enums\MarcRole $marcRole
	 *
	 * @return array<Contributor>
	 */
	public static function GetAllByUrlNameAndMarcRole(array $urlNames, Enums\MarcRole $marcRole): array{
		return Db::Query('select * from Contributors inner join EbookContributors using (ContributorId) where UrlName in ' . Db::CreateSetSql($urlNames) . ' and MarcRole = ? group by UrlName order by field(UrlName, ' . str_replace(['(', ')'], '', Db::CreateSetSql($urlNames)) . ')', array_merge($urlNames, [$marcRole], $urlNames), Contributor::class);
	}

	/**
	 * @throws Exceptions\ContributorNotFoundException If the `Contributor` can't be found.
	 */
	public static function GetByUrlName(string $urlName): Contributor{
		return Db::Query('select * from Contributors where UrlName = ? limit 1', [$urlName], Contributor::class)[0] ?? throw new Exceptions\ContributorNotFoundException();
	}
}
