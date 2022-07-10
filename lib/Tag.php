<?
class Tag{
	public $Name;
	public $Url;
	public $UrlName;

	public function __construct(string $name){
		$this->Name = $name;
		$this->UrlName = Formatter::MakeUrlSafe($this->Name);
		$this->Url = '/subjects/' . $this->UrlName;
	}
}
