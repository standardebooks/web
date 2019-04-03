<?
class Collection{
	public $Name;
	public $Url;

	public function __construct(string $name){
		$this->Name = $name;
		$this->Url = '/collections/' . strtolower(str_replace(' ', '-', Formatter::ToPlainText(Formatter::RemoveDiacritics($this->Name)))) . '/';
	}
}
