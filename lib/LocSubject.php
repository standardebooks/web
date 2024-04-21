<?
class LocSubject extends Tag{
	public int $LocSubjectId;
	public string $Name;

	public function Validate(): void{
		$error = new Exceptions\ValidationException();

		if(strlen($this->Name) > EBOOKS_MAX_STRING_LENGTH){
			$error->Add(new Exceptions\StringTooLongException('LoC subject: '. $this->Name));
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	public function Create(): void{
		$this->Validate();

		Db::Query('
			INSERT into LocSubjects (Name)
			values (?)
		', [$this->Name]);
		$this->LocSubjectId = Db::GetLastInsertedId();
	}

	public static function GetOrCreate(LocSubject $locSubject): LocSubject{
		$result = Db::Query('
				SELECT *
				from LocSubjects
				where Name = ?
			', [$locSubject->Name], 'LocSubject');

		if(isset($result[0])){
			return $result[0];
		}
		else{
			$locSubject->Create();
			return $locSubject;
		}
	}
}
