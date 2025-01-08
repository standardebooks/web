<?
use Ramsey\Uuid\Uuid;
use Safe\DateTimeImmutable;

use function Safe\preg_match;

/**
 * @property array<Payment> $Payments
 * @property bool $IsRegistered A user is "registered" if they have an entry in the `Benefits` table; a password is required to log in.
 * @property Benefits $Benefits
 * @property string $Url
 * @property string $EditUrl
 * @property ?Patron $Patron
 * @property ?NewsletterSubscription $NewsletterSubscription
 * @property ?Payment $LastPayment
 * @property string $DisplayName The `User`'s name, or email, or ID.
 * @property ?string $SortName The `User`'s name in an (attempted) sort order, or `null` if the `User` has no name.
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
	protected ?Payment $_LastPayment;
	protected Benefits $_Benefits;
	protected string $_Url;
	protected string $_EditUrl;
	protected ?Patron $_Patron;
	protected ?NewsletterSubscription $_NewsletterSubscription;
	protected string $_DisplayName;
	protected ?string $_SortName = null;


	// *******
	// GETTERS
	// *******

	protected function GetSortName(): string{
		if(!isset($this->_SortName)){
			if($this->Name !== null){
				preg_match('/\s(?:de |de la |di |van |von )?[^\s]+$/iu', $this->Name, $lastNameMatches);
				if(sizeof($lastNameMatches) == 0){
					$this->SortName = $this->Name;
				}
				else{
					$lastName = trim($lastNameMatches[0]);

					preg_match('/^(.+)' . preg_quote($lastName, '/') . '$/u', $this->Name, $firstNameMatches);

					if(sizeof($firstNameMatches) == 0){
						$this->SortName = $this->Name;
					}
					else{
						$this->_SortName = $lastName . ', ' . trim($firstNameMatches[1]);
					}
				}
			}
			else{
				$this->_SortName = null;
			}
		}

		return $this->_SortName;
	}

	protected function GetDisplayName(): string{
		if(!isset($this->_DisplayName)){
			if($this->Name !== null){
				$this->_DisplayName = $this->Name;
			}
			elseif($this->Email !== null){
				$this->_DisplayName = $this->Email;
			}
			else{
				$this->_DisplayName = 'User #' . $this->UserId;
			}
		}

		return $this->_DisplayName;
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
			}
			catch(Exceptions\PatronNotFoundException){
				$this->_Patron = null;
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

	protected function GetEditUrl(): string{
		if(!isset($this->_EditUrl)){
			$this->_EditUrl = $this->Url . '/edit';
		}

		return $this->_EditUrl;
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

	protected function GetLastPayment(): ?Payment{
		if(!isset($this->_LastPayment)){
			$this->_LastPayment = Db::Query('
							SELECT *
							from Payments
							where UserId = ?
							order by Created desc
							limit 1
						', [$this->UserId], Payment::class)[0] ?? null;
		}

		return $this->_LastPayment;
	}

	protected function GetBenefits(): Benefits{
		if(!isset($this->_Benefits)){
			if(isset($this->UserId)){
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
			else{
				$this->_Benefits = new Benefits();
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
		if($this->Benefits->RequiresPassword && $this->PasswordHash === null){
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
	 * Get a `User` based on either a `UserId`, `Email`, or `Uuid`, or `Name`.
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
		elseif(preg_match('/^[0-9a-f]{8}\-[0-9a-f]{4}\-[0-9a-f]{4}\-[0-9a-f]{4}\-[0-9a-f]{12}$/', $identifier)){
			return User::GetByUuid($identifier);
		}
		else{
			return User::GetByName($identifier);
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
	public static function GetByName(?string $name): User{
		if($name === null){
			throw new Exceptions\UserNotFoundException();
		}

		return Db::Query('
					SELECT *
					from Users
					where Name = ?
				', [$name], User::class)[0] ?? throw new Exceptions\UserNotFoundException();
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
	 * @return array<User>
	 */
	public static function GetAllByCanManageProjects(): array{
		return Db::Query('
					SELECT u.*
					from Users u
					inner join Benefits b
					using (UserId)
					where b.CanManageProjects = true
					order by Name asc
				', [], User::class);
	}

	/**
	 * @return array<User>
	 */
	public static function GetAllByCanReviewProjects(): array{
		return Db::Query('
					SELECT u.*
					from Users u
					inner join Benefits b
					using (UserId)
					where b.CanReviewProjects = true
					order by Name asc
				', [], User::class);
	}

	/**
	 * @return array<stdClass>
	 */
	public static function GetNamesByHasProducedProject(): array{
		return Db::Query('
					SELECT
					distinct (ProducerName)
					from Projects
					order by ProducerName asc
				', []);
	}

	/**
	 * Get a random `User` who is available to be assigned to the given role.
	 *
	 * @param Enums\ProjectRoleType $role The role to select for.
	 * @param array<int> $excludedUserIds Don't include these `UserId` when selecting.
	 *
	 * @throws Exceptions\UserNotFoundException If no `User` is available to be assigned to a `Project`.
	 */
	public static function GetByAvailableForProjectAssignment(Enums\ProjectRoleType $role, array $excludedUserIds = []): User{
		if(sizeof($excludedUserIds) == 0){
			$excludedUserIds = [0];
		}

		// First, check if there are `User`s available for assignment.
		$doUnassignedUsersExist = Db::QueryBool('SELECT exists (select * from ProjectUnassignedUsers where Role = ? and UserId not in ' . Db::CreateSetSql($excludedUserIds) . ')', array_merge([$role], $excludedUserIds));

		// No unassigned `User`s left. Refill the list.
		if(!$doUnassignedUsersExist){
			Db::Query('
					INSERT ignore
					into ProjectUnassignedUsers
					(UserId, Role)
					select
						Users.UserId,
						?
					from Users
					inner join Benefits
					using (UserId)
					where
						Benefits.CanManageProjects = true
						and Benefits.CanBeAutoAssignedToProjects = true
				', [$role]);
		}

		// Now, select a random `User`.
		$user = Db::Query('SELECT u.* from Users u inner join ProjectUnassignedUsers puu using (UserId) where Role = ? and UserId not in ' . Db::CreateSetSql($excludedUserIds) . ' order by rand()', array_merge([$role], $excludedUserIds), User::class)[0] ?? throw new Exceptions\UserNotFoundException();

		// Delete the `User` we just got from the unassigned users list.
		Db::Query('DELETE from ProjectUnassignedUsers where UserId = ? and Role = ?', [$user->UserId, $role]);

		return $user;
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
