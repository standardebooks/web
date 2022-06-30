<?
use Ramsey\Uuid\Uuid;
use Safe\DateTime;

class User extends PropertiesBase{
	public $UserId;
	public $FirstName;
	public $LastName;
	protected $Name = null;
	public $Email;
	public $Created;
	public $Uuid;


	// *******
	// GETTERS
	// *******

	protected function GetName(): string{
		if($this->Name === null){
			$this->Name = $this->FirstName . ' ' . $this->LastName;
		}

		return $this->Name;
	}


	// *******
	// METHODS
	// *******

	public function Create(): void{
		$uuid = Uuid::uuid4();
		$this->Uuid = $uuid->toString();
		$this->Created = new DateTime();

		try{
			Db::Query('INSERT into Users (Email, Name, Uuid, Created) values (?, ?, ?, ?);', [$this->Email, $this->Name, $this->Uuid, $this->Created]);
		}
		catch(PDOException $ex){
			if($ex->errorInfo[1] == 1062){
				// Duplicate unique key; email already in use
				throw new Exceptions\UserExistsException();
			}
			else{
				throw $ex;
			}
		}

		$this->UserId = Db::GetLastInsertedId();
	}


	// ***********
	// ORM METHODS
	// ***********

	public static function Get(?int $userId): User{
		$result = Db::Query('SELECT * from Users where UserId = ?', [$userId], 'User');

		if(sizeof($result) == 0){
			throw new Exceptions\InvalidUserException();
		}

		return $result[0];
	}

	public static function GetByEmail(?string $email): User{
		$result = Db::Query('SELECT * from Users where Email = ?', [$email], 'User');

		if(sizeof($result) == 0){
			throw new Exceptions\InvalidUserException();
		}

		return $result[0];
	}
}
