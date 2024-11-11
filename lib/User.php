<?
use Ramsey\Uuid\Uuid;
use Safe\DateTimeImmutable;

/**
 * @property array<Payment> $Payments
 * @property bool $IsRegistered
 * @property Benefits $Benefits
 */
class User{
	use Traits\Accessor;

	public int $UserId;
	public ?string $Name = null;
	public ?string $Email = null;
	public DateTimeImmutable $Created;
	public string $Uuid;
	public ?string $PasswordHash = null;

	protected bool $_IsRegistered;
	/** @var array<Payment> $_Payments */
	protected array $_Payments;
	protected Benefits $_Benefits;


	// *******
	// GETTERS
	// *******

	/**
	* @return array<Payment>
	*/
	protected function GetPayments(): array{
		if(!isset($this->_Payments)){
			$this->_Payments = Db::Query('
							SELECT *
							from Payments
							where UserId = ?
							order by Created desc
						', [$this->UserId], Payment::class);
		}

		return $this->_Payments;
	}

	protected function GetBenefits(): Benefits{
		if(!isset($this->_Benefits)){
			$result = Db::Query('
						SELECT *
						from Benefits
						where UserId = ?
					', [$this->UserId], Benefits::class);

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
		if(!isset($this->_IsRegistered)){
			// A user is "registered" if they have a benefits entry in the table.
			// This function will fill it out for us.
			$this->GetBenefits();
		}

		return $this->_IsRegistered;
	}


	// *******
	// METHODS
	// *******

	/**
	 * @throws Exceptions\UserExistsException
	 */
	public function Create(?string $password = null): void{
		$uuid = Uuid::uuid4();
		$this->Uuid = $uuid->toString();

		$this->Created = NOW;

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
		catch(Exceptions\DuplicateDatabaseKeyException){
			throw new Exceptions\UserExistsException();
		}

		$this->UserId = Db::GetLastInsertedId();
	}


	// ***********
	// ORM METHODS
	// ***********

	/**
	 * @throws Exceptions\UserNotFoundException
	 */
	public static function Get(?int $userId): User{
		if($userId === null){
			throw new Exceptions\UserNotFoundException();
		}

		return Db::Query('
					SELECT *
					from Users
					where UserId = ?
				', [$userId], User::class)[0] ?? throw new Exceptions\UserNotFoundException();
	}

	/**
	 * @throws Exceptions\UserNotFoundException
	 */
	public static function GetByEmail(?string $email): User{
		if($email === null){
			throw new Exceptions\UserNotFoundException();
		}

		return Db::Query('
					SELECT *
					from Users
					where Email = ?
				', [$email], User::class)[0] ?? throw new Exceptions\UserNotFoundException();
	}

	/**
	 * Get a `User` if they are considered "registered".
	 *
	 * We consider a `User` "registered" if they have a row in the `Benefits` table. Emails without that row may only be signed up for the newsletter, and thus are not considered to be "registered" users.
	 *
	 * @param ?string $identifier Either an email or a UUID (i.e., an api key).
	 *
	 * @throws Exceptions\UserNotFoundException
	 * @throws Exceptions\PasswordRequiredException
	 */
	public static function GetIfRegistered(?string $identifier, ?string $password = null): User{
		if($identifier === null){
			throw new Exceptions\UserNotFoundException();
		}

		$user = Db::Query('
					SELECT u.*
					from Users u
					inner join Benefits using (UserId)
					where u.Email = ?
					    or u.Uuid = ?
				', [$identifier, $identifier], User::class)[0] ?? throw new Exceptions\UserNotFoundException();

		if($user->PasswordHash !== null && $password === null){
			// Indicate that a password is required before we log in
			throw new Exceptions\PasswordRequiredException();
		}

		if($user->PasswordHash !== null && !password_verify($password ?? '', $user->PasswordHash)){
			throw new Exceptions\UserNotFoundException();
		}

		return $user;
	}
}
