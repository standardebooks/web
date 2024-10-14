<?

use Safe\DateTimeImmutable;

use function Safe\preg_match;

class Contributor{
	public ?int $EbookId = null;
	public string $Name;
	public string $UrlName;
	public ?string $SortName = null;
	public ?string $WikipediaUrl = null;
	public ?string $MarcRole = null;
	public ?string $FullName = null;
	public ?string $NacoafUrl = null;
	public ?int $SortOrder = null;

	public static function FromProperties(string $name, string $sortName = null, string $fullName = null, string $wikipediaUrl = null, string $marcRole = null, string $nacoafUrl = null): Contributor{
		$instance = new Contributor();
		$instance->Name = str_replace('\'', '’', $name);
		$instance->UrlName = Formatter::MakeUrlSafe($name);
		$instance->SortName = $sortName;
		$instance->FullName = $fullName;
		$instance->WikipediaUrl = $wikipediaUrl;
		$instance->MarcRole = $marcRole;
		$instance->NacoafUrl = $nacoafUrl;
		return $instance;
	}

	/**
	 * @throws Exceptions\ValidationException
	 */
	public function Validate(): void{
		/** @throws void */
		$now = new DateTimeImmutable();

		$error = new Exceptions\ValidationException();

		if(!isset($this->EbookId)){
			$error->Add(new Exceptions\ContributorEbookIdRequiredException());
		}

		if(isset($this->Name)){
			$this->Name = trim($this->Name);

			if($this->Name == ''){
				$error->Add(new Exceptions\ContributorNameRequiredException());
			}
		}
		else{
			$error->Add(new Exceptions\ContributorNameRequiredException());
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
			if(!preg_match('|https://.*wiki.*|ius', $this->WikipediaUrl)){
				$error->Add(new Exceptions\InvalidContributorWikipediaUrlException('Invalid Contributor WikipediaUrl: ' . $this->WikipediaUrl));
			}

			if(strlen($this->WikipediaUrl) > EBOOKS_MAX_STRING_LENGTH){
				$error->Add(new Exceptions\StringTooLongException('Contributor WikipediaUrl'));
			}
		}

		$this->MarcRole = trim($this->MarcRole ?? '');
		if($this->MarcRole == ''){
			$this->MarcRole = null;
		}

		$this->NacoafUrl = trim($this->NacoafUrl ?? '');
		if($this->NacoafUrl == ''){
			$this->NacoafUrl = null;
		}

		if(isset($this->NacoafUrl)){
			if(!preg_match('|https?://id\.loc\.gov/.*|ius', $this->NacoafUrl)){
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
	 * @throws Exceptions\ValidationException
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
}
