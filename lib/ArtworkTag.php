<?

/**
 * @property string $Url
 */
class ArtworkTag extends PropertiesBase{
	public $TagId;
	public $Name;
	protected $_Url;

	// *******
	// GETTERS
	// *******

	/**
	 * @return string
	 */
	protected function GetUrl(): string{
		if($this->_Url === null){
			$this->_Url = '/artworks?query=' . Formatter::MakeUrlSafe($this->Name);
		}

		return $this->_Url;
	}

	// *******
	// METHODS
	// *******
	protected function Validate(): void{
		$error = new Exceptions\ValidationException();

		if($this->Name === null || strlen($this->Name) === 0){
			$error->Add(new Exceptions\InvalidArtworkTagException());
		}

		if($this->Url === null || strlen($this->Url) === 0){
			$error->Add(new Exceptions\InvalidArtworkTagException());
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

	/**
	 * @throws \Exceptions\ValidationException
	 */
	public function GetOrCreate(): void{
		$this->Validate();

		$result = Db::Query('
				SELECT *
				from Tags
				where Name = ?
			', [$this->Name], 'ArtworkTag');

		if(isset($result[0])){
			$this->TagId = $result[0]->TagId;
			return;
		}

		$this->Create();
	}
}
