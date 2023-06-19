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
			$this->_Url = '/artworks?tag=' . Formatter::MakeUrlSafe($this->Name);
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

	public static function GetOrCreate(?string $name): ArtworkTag{
		if($name === null){
			throw new Exceptions\InvalidArtworkTagException();
		}

		$result = Db::Query('
				SELECT *
				from Tags
				where Name = ?
			', [$name], 'ArtworkTag');

		if(sizeof($result) == 0){
			$artworkTag = new ArtworkTag();
			$artworkTag->Name = $name;
			$artworkTag->Create();
			return $artworkTag;
		}

		return $result[0];
	}
}
