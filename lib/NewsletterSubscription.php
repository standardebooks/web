<?
use Safe\DateTimeImmutable;

/**
 * @property User $User
 * @property Newsletter $Newsletter
 * @property-read string $Url
 * @property-read string $DeleteUrl
 */
class NewsletterSubscription{
	use Traits\Accessor;

	public bool $IsConfirmed = false;
	public int $UserId;
	public int $NewsletterId;
	public DateTimeImmutable $Created;

	protected User $_User;
	protected Newsletter $_Newsletter;
	protected string $_Url;
	protected string $_DeleteUrl;


	// *******
	// GETTERS
	// *******

	protected function GetUrl(): string{
		return $this->_Url ??= '/users/' . $this->User->Uuid . '/newsletter-subscriptions/' . $this->NewsletterId;
	}

	protected function GetDeleteUrl(): string{
		return $this->_DeleteUrl ??= $this->Url . '/delete';
	}

	// *******
	// METHODS
	// *******

	/**
	 * @throws Exceptions\InvalidNewsletterSubscription
	 * @throws Exceptions\NewsletterSubscriptionExistsException
	 */
	public function Create(): void{
		$this->Validate();

		// Do we need to create a `User`?
		try{
			$this->User = User::GetByEmail($this->User->Email);
		}
		catch(Exceptions\UserNotFoundException){
			// User doesn't exist, create the `User`.

			try{
				$this->User->Create();
			}
			catch(Exceptions\UserExistsException | Exceptions\InvalidUserException){
				// `User` exists, pass.
			}
		}

		$this->UserId = $this->User->UserId;
		$this->Created = NOW;

		try{
			Db::Query('
				INSERT into NewsletterSubscriptions (UserId, NewsletterId, IsConfirmed, Created)
				values (?,
				        ?,
				        ?,
				        ?)
			', [$this->UserId, $this->NewsletterId, $this->IsConfirmed, $this->Created]);
		}
		catch(Exceptions\DuplicateDatabaseKeyException){
			throw new Exceptions\NewsletterSubscriptionExistsException();
		}
	}

	/**
	 * @throws Exceptions\InvalidNewsletterSubscription
	 */
	public function Save(): void{
		$this->Validate();

		Db::Query('
			UPDATE NewsletterSubscriptions
			set IsConfirmed = ?,
			where UserId = ?
		', [$this->IsConfirmed, $this->UserId]);
	}

	public function Confirm(): void{
		Db::Query('
			UPDATE NewsletterSubscriptions
			set IsConfirmed = true
			where UserId = ?
			and NewsletterId = ?
		', [$this->UserId, $this->NewsletterId]);
	}

	public function Delete(): void{
		Db::Query('
			DELETE
			from NewsletterSubscriptions
			where UserId = ?
			and NewsletterId = ?
		', [$this->UserId, $this->NewsletterId]);
	}

	public static function DeleteAllByEmail(string $email): void{
		Db::Query('
			DELETE ns.*
			from NewsletterSubscriptions ns
			inner join Users u using(UserId)
			where u.Email = ?
		', [$email]);
	}

	/**
	 * @throws Exceptions\InvalidNewsletterSubscription
	 */
	public function Validate(): void{
		$error = new Exceptions\InvalidNewsletterSubscription();

		if(!isset($this->User) || ($this->User->Email ?? '') == '' || !Validator::IsValidEmail($this->User->Email)){
			$error->Add(new Exceptions\InvalidEmailException());
		}

		if(!isset($this->Newsletter)){
			$error->Add(new Exceptions\NewsletterRequiredException());
		}

		if($error->HasExceptions){
			throw $error;
		}
	}


	// ***********
	// ORM METHODS
	// ***********

	/**
	 * @throws Exceptions\NewsletterSubscriptionNotFoundException
	 */
	public static function GetByUserUuid(?string $uuid, ?int $newsletterId): NewsletterSubscription{
		if($uuid === null || $newsletterId === null){
			throw new Exceptions\NewsletterSubscriptionNotFoundException();
		}

		return Db::Query('
				SELECT ns.*
				from NewsletterSubscriptions ns
				inner join Users u using(UserId)
				where u.Uuid = ?
				and ns.NewsletterId = ?
			', [$uuid, $newsletterId], NewsletterSubscription::class)[0] ?? throw new Exceptions\NewsletterSubscriptionNotFoundException();
	}

	/**
	 * @return array<NewsletterSubscription>
	 */
	public static function GetAllByUserId(?int $userId): array{
		if($userId === null){
			return [];
		}

		return Db::Query('
				SELECT ns.*
				from NewsletterSubscriptions ns
				inner join Users u using(UserId)
				where u.UserId = ?
			', [$userId], NewsletterSubscription::class);
	}
}
