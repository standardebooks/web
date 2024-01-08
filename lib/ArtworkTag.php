<?

class ArtworkTag extends Tag{
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
