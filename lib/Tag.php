<?
class Tag{
	public $Name;
	public $Url;

	public function __construct(string $name){
		$this->Name = $name;
		$this->Url = '/tags/' . strtolower(str_replace(' ', '-', Formatter::ToPlainText($this->Name))) . '/';
	}
}
