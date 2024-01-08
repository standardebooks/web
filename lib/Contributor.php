<?
class Contributor{
	public string $Name;
	public string $UrlName;
	public ?string $SortName = null;
	public ?string $WikipediaUrl = null;
	public ?string $MarcRole = null;
	public ?string $FullName = null;
	public ?string $NacoafUrl = null;

	public function __construct(string $name, string $sortName = null, string $fullName = null, string $wikipediaUrl = null, string $marcRole = null, string $nacoafUrl = null){
		$this->Name = str_replace('\'', '’', $name);
		$this->UrlName = Formatter::MakeUrlSafe($name);
		$this->SortName = $sortName;
		$this->FullName = $fullName;
		$this->WikipediaUrl = $wikipediaUrl;
		$this->MarcRole = $marcRole;
		$this->NacoafUrl = $nacoafUrl;
	}
}
