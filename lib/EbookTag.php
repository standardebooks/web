<?
class EbookTag extends Tag{
	public function __construct(string $name){
		$this->Name = $name;
		$this->UrlName = Formatter::MakeUrlSafe($this->Name);
		$this->_Url = '/subjects/' . $this->UrlName;
	}


	// *******
	// GETTERS
	// *******

	protected function GetUrl(): string{
		if($this->_Url === null){
			$this->_Url = '/subjects/' . $this->UrlName;
		}

		return $this->_Url;
	}
}
