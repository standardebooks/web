<?
use function Safe\preg_match;

/**
 * @property-read ?string $Url The URL of this `Contributor` if their MARC role is `Enums\MarcRole::Author`, or `null` otherwise.
 */
class Contributor{
	use Traits\Accessor;

	public int $EbookId;
	public string $Name;
	public string $UrlName;
	public ?string $SortName = null;
	public ?string $WikipediaUrl = null;
	public Enums\MarcRole $MarcRole;
	public ?string $FullName = null;
	public ?string $NacoafUrl = null;
	public int $SortOrder;

	protected ?string $_Url;


	// *******
	// GETTERS
	// *******

	protected function GetUrl(): ?string{
		if(!isset($this->_Url)){
			if($this->MarcRole == Enums\MarcRole::Author){
				$this->_Url = '/ebooks/' . $this->UrlName;
			}
			else{
				$this->_Url = null;
			}
		}

		return $this->_Url;
	}

	// *******
	// METHODS
	// *******

	/**
	 * @return array<Contributor>
	 */
	public static function GetAllByMarcRole(Enums\MarcRole $marcRole): array{
		return Db::Query('
			SELECT
			*
			from Contributors
			where MarcRole = ?
			and SortName is not null
			group by SortName
			order by SortName asc', [$marcRole], Contributor::class);
	}

	/**
	 * @return array<stdClass>
	 */
	public static function GetAllNamesByMarcRole(Enums\MarcRole $marcRole): array{
		return Db::Query('
			SELECT
			distinct Name
			from Contributors
			where MarcRole = ?
			order by Name asc', [$marcRole]);
	}

	/**
	 * @throws Exceptions\InvalidContributorException
	 */
	public function Validate(): void{
		$error = new Exceptions\InvalidContributorException();

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
				$error->Add(new Exceptions\InvalidContributorWikipediaUrlException('Invalid Contributor WikipediaUrl: ' . $this->WikipediaUrl));
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
				$error->Add(new Exceptions\InvalidContributorNacoafUrlException('Invalid Contributor NacoafUrl: ' . $this->NacoafUrl));
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
	 * @throws Exceptions\InvalidContributorException
	 */
	public function Create(bool $copyFromExistingName): void{
		if($copyFromExistingName){
			try{
				// This is useful when adding new placeholders for authors who already exist with detailed metadata.
				$existingContributor = Contributor::GetByUrlName($this->UrlName);
				$this->SortName = $existingContributor->SortName;
				$this->WikipediaUrl = $existingContributor->WikipediaUrl;
				$this->FullName = $existingContributor->FullName;
				$this->NacoafUrl = $existingContributor->NacoafUrl;
			}
			catch(Exceptions\ContributorNotFoundException){
				// Not found, pass.
			}
		}

		$this->Validate();
		Db::Query('
			INSERT into Contributors (EbookId, Name, UrlName, SortName, WikipediaUrl, MarcRole, FullName,
				NacoafUrl, SortOrder)
			values (?,
				?,
				?,
				?,
				?,
				?,
				?,
				?,
				?)
		', [$this->EbookId, $this->Name, $this->UrlName, $this->SortName, $this->WikipediaUrl, $this->MarcRole, $this->FullName,
			$this->NacoafUrl, $this->SortOrder]);
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
	 * @return array<Contributor>
	 */
	public static function GetDistinctByMarcRole(Enums\MarcRole $marcRole): array{
		return Db::Query('SELECT * from Contributors where MarcRole = ? group by UrlName', [$marcRole], Contributor::class);
	}

	/**
	 * @param array<string> $urlNames
	 * @param Enums\MarcRole $marcRole
	 *
	 * @return array<Contributor>
	 */
	public static function GetAllByUrlNameAndMarcRole(array $urlNames, Enums\MarcRole $marcRole): array{
		return Db::Query('SELECT * from Contributors where UrlName in ' . Db::CreateSetSql($urlNames) . ' and MarcRole = ? group by UrlName order by field(UrlName, ' . str_replace(['(', ')'], '', Db::CreateSetSql($urlNames)) . ')', array_merge($urlNames, [$marcRole], $urlNames), Contributor::class);
	}

	/**
	 * @throws Exceptions\ContributorNotFoundException If the `Contributor` can't be found.
	 */
	public static function GetByUrlName(string $urlName): Contributor{
		return Db::Query('SELECT * from Contributors where UrlName = ? limit 1', [$urlName], Contributor::class)[0] ?? throw new Exceptions\ContributorNotFoundException();
	}
}
