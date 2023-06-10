<?
use Safe\DateTime;

class Artist extends PropertiesBase{
	public $ArtistId;
	public $Name;
	public $DeathYear;

	// *******
	// METHODS
	// *******

	protected function Validate(): void{
		$error = new Exceptions\ValidationException();

		if($this->Name === null || strlen($this->Name) === 0){
			$error->Add(new Exceptions\InvalidArtistException());
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	public function Create(): void{
		$this->Validate();
		Db::Query('
			INSERT into Artists (Name, DeathYear)
			values (?,
			        ?)
		', [$this->Name, $this->DeathYear]);

		$this->ArtistId = Db::GetLastInsertedId();
	}

	public function Delete(): void{
		Db::Query('
			DELETE
			from Artists
			where ArtistId = ?
		', [$this->ArtistId]);
	}
}
