<?

/**
 * @property string $UrlName
 */
class ArtworkTag extends PropertiesBase{
	public $TagId;
	public $Name;
	protected $_UrlName;

	// *******
	// GETTERS
	// *******

	/**
	 * @return string
	 */
	protected function GetUrlName(): string{
		if($this->_UrlName === null){
			$this->_UrlName = Formatter::MakeUrlSafe($this->Name);
		}

		return $this->_UrlName;
	}

	// *******
	// METHODS
	// *******
	protected function Validate(): void{
		$error = new Exceptions\ValidationException();

		if($this->Name === null || strlen($this->Name) === 0){
			$error->Add(new Exceptions\InvalidArtworkTagException());
		}

		if($this->UrlName === null || strlen($this->UrlName) === 0){
			$error->Add(new Exceptions\InvalidArtworkTagException());
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	public function Create(): void{
		$this->Validate();

		Db::Query('
			INSERT into Tags (Name, UrlName)
			values (?,
			        ?)
		', [$this->Name, $this->UrlName]);
		$this->TagId = Db::GetLastInsertedId();
	}

	public static function GetOrCreate(?string $name): ArtworkTag{
		if($name === null){
			throw new Exceptions\InvalidArtworkTagException();
		}
		$urlName = Formatter::MakeUrlSafe($name);

		$result = Db::Query('
				SELECT *
				from Tags
				where UrlName = ?
			', [$urlName], 'ArtworkTag');

		if(sizeof($result) == 0){
			$artworkTag = new ArtworkTag();
			$artworkTag->Name = $name;
			$artworkTag->Create();
			return $artworkTag;
		}

		return $result[0];
	}
}
