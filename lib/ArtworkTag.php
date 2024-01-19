<?
use function Safe\preg_match;

class ArtworkTag extends Tag{
	// *******
	// SETTERS
	// *******

	// protected function SetName($name): void{
	// 	$this->_Name =
	// }

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
	public function Validate(): void{
		$error = new Exceptions\ValidationException();

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

		// TODO: Remove this once all legacy artworks are cleaned up and approved.
		// 'todo' is a reserved tag for legacy artworks.
		if($this->Name == 'todo'){
			$error->Add(new Exceptions\InvalidArtworkTagNameException());
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
	public static function GetOrCreate(ArtworkTag $artworkTag): ArtworkTag{
		$result = Db::Query('
				SELECT *
				from Tags
				where Name = ?
			', [$artworkTag->Name], 'ArtworkTag');

		if(isset($result[0])){
			return $result[0];
		}
		else{
			$artworkTag->Create();
			return $artworkTag;
		}
	}
}
