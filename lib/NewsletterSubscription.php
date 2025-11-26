<?
use Safe\DateTimeImmutable;

/**
 * @property User $User
 * @property Newsletter $Newsletter
 * @property-read string $Url
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


	// *******
	// GETTERS
	// *******

	protected function GetUrl(): string{
		return $this->_Url ??= '/newsletter/subscriptions/' . $this->User->Uuid;
	}


	// *******
	// METHODS
	// *******

	/**
	 * @throws Exceptions\InvalidNewsletterSubscription
	 * @throws Exceptions\NewsletterSubscriptionExistsException
	 */
	public function Create(?string $expectedCaptcha = null, ?string $receivedCaptcha = null): void{
		$this->Validate($expectedCaptcha, $receivedCaptcha);

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
			', [$this->User->UserId, $this->NewsletterId, false, $this->Created]);
		}
		catch(Exceptions\DuplicateDatabaseKeyException){
			throw new Exceptions\NewsletterSubscriptionExistsException();
		}

		// Send the double opt-in confirmation email.
		$this->SendConfirmationEmail();
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

	public function SendConfirmationEmail(): void{
		if($this->User->Email !== null){
			$em = new QueuedEmailMessage(true);
			$em->To = $this->User->Email;
			$em->ToName = $this->User->Name;
			$em->Subject = 'Action required: confirm your newsletter subscription';
			$em->BodyHtml = Template::EmailNewsletterConfirmation(subscription: $this, isSubscribedToSummary: $this->IsSubscribedToSummary, isSubscribedToNewsletter: $this->IsSubscribedToNewsletter);
			$em->BodyText = Template::EmailNewsletterConfirmationText(subscription: $this, isSubscribedToSummary: $this->IsSubscribedToSummary, isSubscribedToNewsletter: $this->IsSubscribedToNewsletter);
			$em->Send();
		}
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
	public function Validate(?string $expectedCaptcha = null, ?string $receivedCaptcha = null): void{
		$error = new Exceptions\InvalidNewsletterSubscription();

		if(!isset($this->User) || $this->User->Email == '' || !Validator::IsValidEmail($this->User->Email)){
			$error->Add(new Exceptions\InvalidEmailException());
		}

		if(!isset($this->Newsletter)){
			$error->Add(new Exceptions\NewsletterRequiredException());
		}

		if($expectedCaptcha !== null){
			if($expectedCaptcha === '' || mb_strtolower($expectedCaptcha) !== mb_strtolower($receivedCaptcha ?? '')){
				$error->Add(new Exceptions\InvalidCaptchaException());
			}
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
	public static function GetByUserUuid(?string $uuid): NewsletterSubscription{
		if($uuid === null){
			throw new Exceptions\NewsletterSubscriptionNotFoundException();
		}

		return Db::Query('
				SELECT ns.*
				from NewsletterSubscriptions ns
				inner join Users u using(UserId)
				where u.Uuid = ?
			', [$uuid], NewsletterSubscription::class)[0] ?? throw new Exceptions\NewsletterSubscriptionNotFoundException();
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
