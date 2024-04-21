<?
use function Safe\preg_replace;

class Collection{
	public string $Name;
	public string $UrlName;
	public string $Url;
	public ?int $SequenceNumber = null;
	public ?string $Type = null;

	public function __construct(string $name){
		$this->Name = $name;
		$this->UrlName = Formatter::MakeUrlSafe($this->Name);
		$this->Url = '/collections/' . $this->UrlName;
	}

	public function GetSortedName(): string{
		return preg_replace('/^(the|and|a|)\s/ius', '', $this->Name);
	}
}
