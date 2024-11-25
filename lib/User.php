<?
use Ramsey\Uuid\Uuid;
use Safe\DateTimeImmutable;

use function Safe\preg_match;

/**
 * @property array<Payment> $Payments
 * @property bool $IsRegistered A user is "registered" if they have an entry in the `Benefits` table; a password is required to log in.
 * @property Benefits $Benefits
 * @property string $Url
 * @property bool $IsPatron
 * @property ?Patron $Patron
 * @property ?NewsletterSubscription $NewsletterSubscription
 */
class User{
	use Traits\Accessor;
	use Traits\PropertyFromHttp;

	public int $UserId;
	public ?string $Name = null;
	public ?string $Email = null;
	public DateTimeImmutable $Created;
	public DateTimeImmutable $Updated;
	public string $Uuid;
	public ?string $PasswordHash = null;

	protected bool $_IsRegistered;
	/** @var array<Payment> $_Payments */
	protected array $_Payments;
	protected Benefits $_Benefits;
	protected string $_Url;
	protected bool $_IsPatron;
	protected ?Patron $_Patron;
	protected ?NewsletterSubscription $_NewsletterSubscription;


	// *******
	// GETTERS
	// *******

	protected function GetIsPatron(): bool{
		if(!isset($this->_IsPatron)){
			$this->GetPatron();
		}

		return $this->_IsPatron;
	}

	protected function GetNewsletterSubscription(): ?NewsletterSubscription{
		if(!isset($this->_NewsletterSubscription)){
			try{
				$this->_NewsletterSubscription = NewsletterSubscription::GetByUserId($this->UserId);
			}
			catch(Exceptions\NewsletterSubscriptionNotFoundException){
				$this->_NewsletterSubscription = null;
			}
		}

		return $this->_NewsletterSubscription;
	}

	protected function GetPatron(): ?Patron{
		if(!isset($this->_Patron)){
			try{
				$this->_Patron = Patron::Get($this->UserId);
				$this->IsPatron = true;
			}
			catch(Exceptions\PatronNotFoundException){
				$this->_Patron = null;
				$this->IsPatron = false;
			}
		}

		return $this->_Patron;
	}

	protected function GetUrl(): string{
		if(!isset($this->_Url)){
			$this->_Url = '/users/' . $this->UserId;
		}

		return $this->_Url;
	}

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
			// A user is "registered" if they have an entry in the `Benefits` table.
			// This function will fill it out for us.
			$this->GetBenefits();
		}

		return $this->_IsRegistered;
	}


	// *******
	// METHODS
	// *******

	/**
	 * @throws Exceptions\InvalidUserException
	 */
	public function Validate(): void{
		$error = new Exceptions\InvalidUserException();

		if(!isset($this->Email)){
			$error->Add(new Exceptions\EmailRequiredException());
		}
		else{
			if(filter_var($this->Email, FILTER_VALIDATE_EMAIL) === false){
				$error->Add(new Exceptions\InvalidEmailException('Email is invalid.'));
			}
		}

		if(!isset($this->Uuid)){
			$error->Add(new Exceptions\UuidRequiredException());
		}
		else{
			if(!preg_match('/^[0-9a-f]{8}\-[0-9a-f]{4}\-[0-9a-f]{4}\-[0-9a-f]{4}\-[0-9a-f]{12}$/', $this->Uuid)){
				$error->Add(new Exceptions\InvalidUuidException());
			}
		}

		if(trim($this->Name ?? '') == ''){
			$this->Name = null;
		}

		if(trim($this->PasswordHash ?? '') == ''){
			$this->PasswordHash = null;
		}

		// Some benefits require this `User` to have a password set.
		if($this->Benefits?->RequiresPassword && $this->PasswordHash === null){
			$error->Add(new Exceptions\BenefitsRequirePasswordException());
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	public function GenerateUuid(): void{
		$uuid = Uuid::uuid4();
		$this->Uuid = $uuid->toString();
	}


	/**
	 * @throws Exceptions\InvalidUserException
	 * @throws Exceptions\UserExistsException
	 */
	public function Create(?string $password = null): void{
		$this->GenerateUuid();

		$this->Validate();

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

	/**
	 * @throws Exceptions\InvalidUserException
	 * @throws Exceptions\UserExistsException
	 */
	public function Save(): void{
		$this->Validate();

		$this->Updated = NOW;

		try{
			Db::Query('
					UPDATE Users
					set Email = ?, Name = ?, Uuid = ?, Updated = ?, PasswordHash = ?
					where
					UserId = ?
				', [$this->Email, $this->Name, $this->Uuid, $this->Updated, $this->PasswordHash, $this->UserId]);

			if($this->IsRegistered){
				$this->Benefits->Save();
			}
			elseif($this->Benefits->HasBenefits){
				$this->Benefits->UserId = $this->UserId;
				$this->Benefits->Create();
				$this->IsRegistered = true;
			}
		}
		catch(Exceptions\DuplicateDatabaseKeyException){
			throw new Exceptions\UserExistsException();
		}
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
	 * Get a `User` based on either a `UserId`, `Email`, or `Uuid`.
	 *
	 * @throws Exceptions\UserNotFoundException
	 */
	public static function GetByIdentifier(?string $identifier): User{
		if($identifier === null){
			throw new Exceptions\UserNotFoundException();
		}

		if(ctype_digit($identifier)){
			return User::Get(intval($identifier));
		}
		elseif(mb_stripos($identifier, '@') !== false){
			return User::GetByEmail($identifier);
		}
		elseif(mb_stripos($identifier, '-') !== false){
			return User::GetByUuid($identifier);
		}
		else{
			throw new Exceptions\UserNotFoundException();
		}
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
	 * @throws Exceptions\UserNotFoundException
	 */
	public static function GetByUuid(?string $uuid): User{
		if($uuid === null){
			throw new Exceptions\UserNotFoundException();
		}

		return Db::Query('
					SELECT *
					from Users
					where Uuid = ?
				', [$uuid], User::class)[0] ?? throw new Exceptions\UserNotFoundException();
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
			// Indicate that a password is required before we log in.
			throw new Exceptions\PasswordRequiredException();
		}

		if($user->PasswordHash !== null && !password_verify($password ?? '', $user->PasswordHash)){
			throw new Exceptions\UserNotFoundException();
		}

		return $user;
	}

	public function FillFromHttpPost(): void{
		$this->PropertyFromHttp('Name');
		$this->PropertyFromHttp('Email');
		$this->PropertyFromHttp('Uuid');

		$this->Benefits->FillFromHttpPost();
	}
}
