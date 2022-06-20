<?
use Ramsey\Uuid\Uuid;

class User extends PropertiesBase{
	public $UserId;
	public $FirstName;
	protected $DisplayFirstName = null;
	public $LastName;
	protected $DisplayLastName = null;
	protected $Name = null;
	protected $DisplayName = null;
	public $Email;
	protected $DisplayEmail;
	public $Timestamp;
	public $Uuid;

	public static function Get(int $userId): ?User{
		$result = Db::Query('select * from Users where UserId = ?', [$userId], 'User');

		return $result[0] ?? null;
	}

	protected function GetName(): string{
		if($this->Name === null){
			$this->Name = $this->FirstName . ' ' . $this->LastName;
		}

		return $this->Name;
	}

	public function Create(): void{
		$uuid = Uuid::uuid4();
		$this->Uuid = $uuid->toString();

		try{
			Db::Query('insert into Users (Email, Name, Uuid, Timestamp) values (?, ?, ?, utc_timestamp());', [$this->Email, $this->Name, $this->Uuid]);
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
}
