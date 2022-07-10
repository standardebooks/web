<?
use Ramsey\Uuid\Uuid;
use Safe\DateTime;

/**
 * @property Array<Payment> $Payments
 */
class User extends PropertiesBase{
	public $UserId;
	public $Name;
	public $Email;
	public $Created;
	public $Uuid;
	protected $_Payments = null;


	// *******
	// GETTERS
	// *******

	/**
	* @return array<Payment>
	*/
	protected function GetPayments(): array{
		if($this->_Payments === null){
			$this->_Payments = Db::Query('select * from Payments where UserId = ? order by Created desc', [$this->UserId], 'Payment');
		}

		return $this->_Payments;
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
		if($email === null){
			throw new Exceptions\InvalidUserException();
		}

		$result = Db::Query('SELECT * from Users where Email = ?', [$email], 'User');

		if(sizeof($result) == 0){
			throw new Exceptions\InvalidUserException();
		}

		return $result[0];
	}

	// Get a user if by either email or uuid, ONLY IF they're either a patron or have a valid API key.
	public static function GetByPatronIdentifier(?string $identifier): User{
		if($identifier === null){
			throw new Exceptions\InvalidUserException();
		}

		$result = Db::Query('SELECT u.* from Patrons p inner join Users u using (UserId) where p.Ended is null and (u.Email = ? or u.Uuid = ?)
			union
			select u.* from ApiKeys fu inner join Users u using (UserId) where fu.Ended is null and (u.Email = ? or u.Uuid = ?)', [$identifier, $identifier, $identifier, $identifier], 'User');

		if(sizeof($result) == 0){
			throw new Exceptions\InvalidUserException();
		}

		return $result[0];
	}
}
