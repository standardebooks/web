<?
use Safe\DateTimeImmutable;

/**
 * @property User $User
 * @property Newsletter $Newsletter
 * @property-read string $Url
 * @property-read string $DeleteUrl
 */
final class NewsletterSubscription{
	use Traits\Accessor;
	use Traits\FromRow;

	public bool $IsConfirmed = false;
	public int $UserId;
	public int $NewsletterId;
	/** `NewsletterSubscriptions` that are deleted are kept for some time longer with `IsVisible` set to **`FALSE`**, to prevent spammers from flooding an email address by repeatedly subscribing and unsubscribing. */
	public bool $IsVisible = true;
	public DateTimeImmutable $CreatedAt;
	public DateTimeImmutable $UpdatedAt;

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
		return $this->_DeleteUrl ??= $this->Url . '?_method=' . Enums\HttpMethod::Delete->value;
	}

	// *******
	// METHODS
	// *******

	/**
	 * @throws Exceptions\InvalidNewsletterSubscription
	 * @throws Exceptions\EmailBounceExistsException
	 * @throws Exceptions\NewsletterSubscriptionExistsException If the subscription already exists or is created by a concurrent request.
	 * @throws Exceptions\UserInvalidException If a new `User` cannot be created from the subscription email address.
	 * @throws Exceptions\UserNotFoundException If a concurrently-created `User` cannot be retrieved.
	 */
	public function Create(): void{
		$this->Validate();

		$hasEmailBounced = Db::QueryBool('select exists (select * from EmailBounces where Email = ? and IsActive = true)', [$this->User->Email]);

		if($hasEmailBounced){
			throw new Exceptions\EmailBounceExistsException('An email we sent to this email address bounced back or was marked as spam. We can’t send email to this email address anymore.');
		}

		// Do we need to create a `User`?
		try{
			$this->User = User::GetByEmail($this->User->Email);
		}
		catch(Exceptions\UserNotFoundException){
			// User doesn't exist, create the `User`.

			try{
				$this->User->Create();
			}
			catch(Exceptions\UserExistsException){
				// A concurrent request created the `User`, so retrieve it.
				$this->User = User::GetByEmail($this->User->Email);
			}
		}

		$this->UserId = $this->User->UserId;
		$this->CreatedAt = NOW;

		// Existing subscriptions don't count against the rate limit because they can't trigger another confirmation email.
		$subscriptionExists = Db::QueryBool('select exists (select * from NewsletterSubscriptions where UserId = ? and NewsletterId = ?)', [$this->UserId, $this->NewsletterId]);
		if($subscriptionExists){
			throw new Exceptions\NewsletterSubscriptionExistsException();
		}

		try{
			Db::Query('
					insert into NewsletterSubscriptions (UserId, NewsletterId, IsConfirmed, IsVisible, CreatedAt)
				values (?,
				        ?,
				        ?,
				        ?,
				        ?)
				', [$this->UserId, $this->NewsletterId, $this->IsConfirmed, $this->IsVisible, $this->CreatedAt]);
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
			update NewsletterSubscriptions
			set IsConfirmed = ?, IsVisible = ?
			where UserId = ?
		', [$this->IsConfirmed, $this->IsVisible, $this->UserId]);
	}

	public function Confirm(): void{
		Db::Query('
			update NewsletterSubscriptions
			set IsConfirmed = true
			where UserId = ?
			and NewsletterId = ?
		', [$this->UserId, $this->NewsletterId]);
	}

	public function Delete(): void{
		Db::Query('
			update
			NewsletterSubscriptions
			set IsVisible = false
			where UserId = ?
			and NewsletterId = ?
		', [$this->UserId, $this->NewsletterId]);
	}

	public static function DeleteAllByEmail(?string $email): void{
		if($email === null){
			return;
		}

		Db::Query('
			update NewsletterSubscriptions ns
			inner join Users u using(UserId)
			set ns.IsVisible = false
			where u.Email = ?
		', [$email]);
	}

	/**
	 * @throws Exceptions\InvalidNewsletterSubscription
	 */
	public function Validate(): void{
		$error = new Exceptions\InvalidNewsletterSubscription();

		if(!isset($this->User->Email)){
			$error->Add(new Exceptions\EmailAddressInvalidException());
		}
		else{
			try{
				$this->User->Email->Validate();
			}
			catch(Exceptions\EmailAddressInvalidException $ex){
				$error->Add($ex);
			}
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
				select ns.*
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
				select ns.*
				from NewsletterSubscriptions ns
				inner join Users u using(UserId)
				where u.UserId = ?
				and ns.IsVisible = true
			', [$userId], NewsletterSubscription::class);
	}

	/**
	 * Creates a `NewsletterSubscription` from a multi table array containing a `NewsletterSubscription` and a `User`.
	 *
	 * @param array<string, stdClass> $row
	 */
	public static function FromMultiTableRow(array $row): NewsletterSubscription{
		$object = NewsletterSubscription::FromRow($row['NewsletterSubscriptions']);

		if(isset($row['Users'])){
			$object->User = User::FromRow($row['Users']);
		}

		return $object;
	}
}
