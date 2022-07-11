<?

use function Safe\preg_replace;

class Collection{
	public $Name;
	public $Url;
	public $SequenceNumber = null;
	public $Type = null;

	public function __construct(string $name){
		$this->Name = $name;
		$this->Url = '/collections/' . Formatter::MakeUrlSafe($this->Name);
	}

	public function GetSortedName(): string{
		return preg_replace('/^(the|and|a|)\s/ius', '', $this->Name);
	}
}
