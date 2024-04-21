<?
class EbookTag extends Tag{
	// *******
	// GETTERS
	// *******
	protected function GetUrlName(): string{
		if($this->_UrlName === null){
			$this->_UrlName = Formatter::MakeUrlSafe($this->Name);
		}

		return $this->_UrlName;
	}

	protected function GetUrl(): string{
		if($this->_Url === null){
			$this->_Url = '/subjects/' . $this->UrlName;
		}

		return $this->_Url;
	}

	// *******
	// METHODS
	// *******
	public function Validate(): void{
		$error = new Exceptions\ValidationException();

		if(strlen($this->Name) > EBOOKS_MAX_STRING_LENGTH){
			$error->Add(new Exceptions\StringTooLongException('Ebook tag: '. $this->Name));
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	public function Create(): void{
		$this->Validate();

		Db::Query('
			INSERT into Tags (Name)
			values (?)
		', [$this->Name]);
		$this->TagId = Db::GetLastInsertedId();
	}

	public static function GetOrCreate(EbookTag $ebookTag): EbookTag{
		$result = Db::Query('
				SELECT *
				from Tags
				where Name = ?
			', [$ebookTag->Name], 'EbookTag');

		if(isset($result[0])){
			return $result[0];
		}
		else{
			$ebookTag->Create();
			return $ebookTag;
		}
	}
}
