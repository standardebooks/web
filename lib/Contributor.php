<?
class Contributor{
	public string $Name;
	public string $UrlName;
	public ?string $SortName = null;
	public ?string $WikipediaUrl = null;
	public ?string $MarcRole = null;
	public ?string $FullName = null;
	public ?string $NacoafUrl = null;

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
}
