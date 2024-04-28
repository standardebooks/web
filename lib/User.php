<?
use Ramsey\Uuid\Uuid;
use Safe\DateTimeImmutable;

/**
 * @property Array<Payment> $Payments
 * @property ?bool $IsRegistered
 * @property Benefits $Benefits
 * @property ?array<Payment> $_Payments
 */
class User extends Accessor{
	public int $UserId;
	public ?string $Name = null;
	public ?string $Email = null;
	public DateTimeImmutable $Created;
	public string $Uuid;
	public ?string $PasswordHash = null;
	protected ?bool $_IsRegistered = null;
	protected $_Payments = null;
	protected ?Benefits $_Benefits = null;

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

	protected function GetIsRegistered(): ?bool{
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

	public function Create(?string $password = null): void{
		$uuid = Uuid::uuid4();
		$this->Uuid = $uuid->toString();
		$this->Created = new DateTimeImmutable();

		$this->PasswordHash = null;
		if($password !== null){
			$this->PasswordHash = password_hash($password, PASSWORD_DEFAULT);
		}

		try{
			Db::Query('
					INSERT into Users (Email, Name, Uuid, Created, PasswordHash)
					values (?,
					        ?,
					        ?,
					        ?,
					        ?)
				', [$this->Email, $this->Name, $this->Uuid, $this->Created, $this->PasswordHash]);
		}
		catch(PDOException $ex){
			if(($ex->errorInfo[1] ?? 0) == 1062){
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
		if($userId === null){
			throw new Exceptions\UserNotFoundException();
		}

		$result = Db::Query('
					SELECT *
					from Users
					where UserId = ?
				', [$userId], 'User');

		if(sizeof($result) == 0){
			throw new Exceptions\UserNotFoundException();
		}

		return $result[0];
	}

	public static function GetByEmail(?string $email): User{
		if($email === null){
			throw new Exceptions\UserNotFoundException();
		}

		$result = Db::Query('
					SELECT *
					from Users
					where Email = ?
				', [$email], 'User');

		if(sizeof($result) == 0){
			throw new Exceptions\UserNotFoundException();
		}

		return $result[0];
	}

	public static function GetIfRegistered(?string $identifier, ?string $password = null): User{
		// We consider a user "registered" if they have a row in the Benefits table.
		// Emails without that row may only be signed up for the newsletter and thus are not "registered" users
		// The identifier is either an email or a UUID (api key)
		if($identifier === null){
			throw new Exceptions\UserNotFoundException();
		}

		$result = Db::Query('
					SELECT u.*
					from Users u
					inner join Benefits using (UserId)
					where u.Email = ?
					    or u.Uuid = ?
				', [$identifier, $identifier], 'User');

		if(sizeof($result) == 0){
			throw new Exceptions\UserNotFoundException();
		}

		if($result[0]->PasswordHash !== null && $password === null){
			// Indicate that a password is required before we log in
			throw new Exceptions\PasswordRequiredException();
		}

		if($result[0]->PasswordHash !== null && !password_verify($password ?? '', $result[0]->PasswordHash)){
			throw new Exceptions\UserNotFoundException();
		}

		return $result[0];
	}
}
