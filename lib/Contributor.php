<?
use function Safe\preg_match;

/**
 * @property ?string $Url The URL of this `Contributor` if their MARC role is `Enums\MarcRole::Author`, or **null** otherwise.
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
	public static function GetAllAuthorNames(): array{
		return Db::Query('
			SELECT DISTINCT Name
			from Contributors
			where MarcRole = "aut"
			order by Name asc', [], Contributor::class);
	}

	/**
	 * @return array<Contributor>
	 */
	public static function GetAllTranslatorNames(): array{
		return Db::Query('
			SELECT DISTINCT Name
			from Contributors
			where MarcRole = "trl"
			order by Name asc', [], Contributor::class);
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
	public function Create(): void{
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


	// ***********
	// ORM METHODS
	// ***********

	/**
	 * @return array<Contributor>
	 */
	public static function GetDistinctByMarcRole(Enums\MarcRole $marcRole): array{
		return Db::Query('SELECT * from Contributors where MarcRole = ? group by UrlName', [$marcRole], Contributor::class);
	}
}
