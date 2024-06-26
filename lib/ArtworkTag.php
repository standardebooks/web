<?
use function Safe\preg_match;
use function Safe\preg_replace;

class ArtworkTag extends Tag{
	public function __construct(){
		$this->Type = 'artwork';
	}

	// *******
	// GETTERS
	// *******

	protected function GetUrl(): string{
		if($this->_Url === null){
			$this->_Url = '/artworks?query=' . Formatter::MakeUrlSafe($this->Name);
		}

		return $this->_Url;
	}

	// *******
	// METHODS
	// *******

	/**
	 * @throws Exceptions\InvalidArtworkTagException
	 */
	public function Validate(): void{
		$error = new Exceptions\InvalidArtworkTagException($this->Name);

		$this->Name = mb_strtolower(trim($this->Name));
		// Collapse spaces into one
		$this->Name = preg_replace('/[\s]+/ius', ' ', $this->Name);

		if(strlen($this->Name) == 0){
			$error->Add(new Exceptions\InvalidArtworkTagNameException());
		}

		if(strlen($this->Name) > ARTWORK_MAX_STRING_LENGTH){
			$error->Add(new Exceptions\StringTooLongException('Artwork tag: '. $this->Name));
		}

		if(preg_match('/[^\sa-z0-9]/ius', $this->Name)){
			$error->Add(new Exceptions\InvalidArtworkTagNameException());
		}

		if($this->Type != 'artwork'){
			$error->Add(new Exceptions\InvalidArtworkTagTypeException($this->Type));
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	/**
	 * @throws Exceptions\InvalidArtworkTagException
	 */
	public function Create(): void{
		$this->Validate();

		Db::Query('
			INSERT into Tags (Name, Type)
			values (?,
				?)
		', [$this->Name, $this->Type]);
		$this->TagId = Db::GetLastInsertedId();
	}

	/**
	 * @throws Exceptions\InvalidArtworkTagException
	 */
	public static function GetOrCreate(ArtworkTag $artworkTag): ArtworkTag{
		$result = Db::Query('
				SELECT *
				from Tags
				where Name = ?
				and Type = "artwork"
			', [$artworkTag->Name], ArtworkTag::class);

		if(isset($result[0])){
			return $result[0];
		}
		else{
			$artworkTag->Create();
			return $artworkTag;
		}
	}
}
