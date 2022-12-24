<?
use Ramsey\Uuid\Uuid;
use Safe\DateTime;

/**
 * @property Array<Payment> $Payments
 * @property Benefits $Benefits
 */
class User extends PropertiesBase{
	public $UserId;
	public $Name;
	public $Email;
	public $Created;
	public $Uuid;
	protected $_IsRegistered = null;
	protected $_Payments = null;
	protected $_Benefits = null;


	// *******
	// GETTERS
	// *******

	/**
	* @return array<Payment>
	*/
	protected function GetPayments(): array{
		if($this->_Payments === null){
			$this->_Payments = Db::Query('
							SELECT *
							from Payments
							where UserId = ?
							order by Created desc
						', [$this->UserId], 'Payment');
		}

		return $this->_Payments;
	}

	protected function GetBenefits(): Benefits{
		if($this->_Benefits === null){
			$result = Db::Query('
						SELECT *
						from Benefits
						where UserId = ?
					', [$this->UserId], 'Benefits');

			if(sizeof($result) == 0){
				$this->_Benefits = new Benefits();
				$this->_IsRegistered = false;
			}
			else{
				$this->_Benefits = $result[0];
				$this->_IsRegistered = true;
			}
		}

		return $this->_Benefits;
	}

	protected function GetIsRegistered(): bool{
		if($this->_IsRegistered === null){
			// A user is "registered" if they have a benefits entry in the table.
			// This function will fill it out for us.
			$this->GetBenefits();
		}

		return $this->_IsRegistered;
	}


	// *******
	// METHODS
	// *******

	public function Create(): void{
		$uuid = Uuid::uuid4();
		$this->Uuid = $uuid->toString();
		$this->Created = new DateTime();

		try{
			Db::Query('
					INSERT into Users (Email, Name, Uuid, Created)
					values (?,
					        ?,
					        ?,
					        ?)
				', [$this->Email, $this->Name, $this->Uuid, $this->Created]);
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
		$result = Db::Query('
					SELECT *
					from Users
					where UserId = ?
				', [$userId], 'User');

		if(sizeof($result) == 0){
			throw new Exceptions\InvalidUserException();
		}

		return $result[0];
	}

	public static function GetByEmail(?string $email): User{
		if($email === null){
			throw new Exceptions\InvalidUserException();
		}

		$result = Db::Query('
					SELECT *
					from Users
					where Email = ?
				', [$email], 'User');

		if(sizeof($result) == 0){
			throw new Exceptions\InvalidUserException();
		}

		return $result[0];
	}

	public static function GetIfRegistered(?string $identifier): User{
		// We consider a user "registered" if they have a row in the Benefits table.
		// Emails without that row may only be signed up for the newsletter and thus are not "registered" users
		// The identifier is either an email or a UUID (api key)
		if($identifier === null){
			throw new Exceptions\InvalidUserException();
		}

		$result = Db::Query('
					SELECT u.*
					from Users u
					inner join Benefits using (UserId)
					where u.Email = ?
					    or u.Uuid = ?
				', [$identifier, $identifier], 'User');

		if(sizeof($result) == 0){
			throw new Exceptions\InvalidUserException();
		}

		return $result[0];
	}
}
