<?
class Collection{
	public $Name;
	public $Url;
	public $SequenceNumber = null;
	public $Type = null;

	public function __construct(string $name){
		$this->Name = $name;
		$this->Url = '/collections/' . Formatter::MakeUrlSafe($this->Name);
	}
}
