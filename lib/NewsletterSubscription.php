<?
use Safe\DateTimeImmutable;

/**
 * @property User $User
 * @property string $Url
 */
class NewsletterSubscription{
	use Traits\Accessor;

	public bool $IsConfirmed = false;
	public bool $IsSubscribedToSummary = false;
	public bool $IsSubscribedToNewsletter = false;
	public ?int $UserId = null;
	public DateTimeImmutable $Created;

	protected ?User $_User;
	protected string $_Url;


	// *******
	// GETTERS
	// *******

	protected function GetUrl(): string{
		if(!isset($this->_Url)){
			$this->_Url = '/newsletter/subscriptions/' . $this->User->Uuid;
		}

		return $this->_Url;
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

		// Do we need to create a user?
		try{
			$this->User = User::GetByEmail($this->User->Email);
		}
		catch(Exceptions\UserNotFoundException){
			// User doesn't exist, create the user

			try{
				$this->User->Create();
			}
			catch(Exceptions\UserExistsException){
				// User exists, pass
			}
		}

		$this->UserId = $this->User->UserId;

		$this->Created = NOW;

		try{
			Db::Query('
				INSERT into NewsletterSubscriptions (UserId, IsConfirmed, IsSubscribedToNewsletter, IsSubscribedToSummary, Created)
				values (?,
				        ?,
				        ?,
				        ?,
				        ?)
			', [$this->User->UserId, false, $this->IsSubscribedToNewsletter, $this->IsSubscribedToSummary, $this->Created]);
		}
		catch(Exceptions\DuplicateDatabaseKeyException){
			throw new Exceptions\NewsletterSubscriptionExistsException();
		}

		// Send the double opt-in confirmation email
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
			    IsSubscribedToNewsletter = ?,
			    IsSubscribedToSummary = ?
			where UserId = ?
		', [$this->IsConfirmed, $this->IsSubscribedToNewsletter, $this->IsSubscribedToSummary, $this->UserId]);
	}

	public function SendConfirmationEmail(): void{
		$em = new Email(true);
		$em->PostmarkStream = EMAIL_POSTMARK_STREAM_BROADCAST;
		$em->To = $this->User->Email ?? '';
		if($this->User->Name !== null && $this->User->Name != ''){
			$em->ToName = $this->User->Name;
		}
		$em->Subject = 'Action required: confirm your newsletter subscription';
		$em->Body = Template::EmailNewsletterConfirmation(['subscription' => $this, 'isSubscribedToSummary' => $this->IsSubscribedToSummary, 'isSubscribedToNewsletter' => $this->IsSubscribedToNewsletter]);
		$em->TextBody = Template::EmailNewsletterConfirmationText(['subscription' => $this, 'isSubscribedToSummary' => $this->IsSubscribedToSummary, 'isSubscribedToNewsletter' => $this->IsSubscribedToNewsletter]);
		$em->Send();
	}

	public function Confirm(): void{
		Db::Query('
			UPDATE NewsletterSubscriptions
			set IsConfirmed = true
			where UserId = ?
		', [$this->UserId]);
	}

	public function Delete(): void{
		Db::Query('
			DELETE
			from NewsletterSubscriptions
			where UserId = ?
		', [$this->UserId]);
	}


	/**
	 * @throws Exceptions\InvalidNewsletterSubscription
	 */
	public function Validate(?string $expectedCaptcha = null, ?string $receivedCaptcha = null): void{
		$error = new Exceptions\InvalidNewsletterSubscription();

		if($this->User === null || $this->User->Email == '' || !filter_var($this->User->Email, FILTER_VALIDATE_EMAIL)){
			$error->Add(new Exceptions\InvalidEmailException());
		}

		if(!$this->IsSubscribedToSummary && !$this->IsSubscribedToNewsletter){
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
	public static function Get(?string $uuid): NewsletterSubscription{
		if($uuid === null){
			throw new Exceptions\NewsletterSubscriptionNotFoundException();
		}

		$result = Db::Query('
				SELECT ns.*
				from NewsletterSubscriptions ns
				inner join Users u using(UserId)
				where u.Uuid = ?
			', [$uuid], NewsletterSubscription::class);

		return $result[0] ?? throw new Exceptions\NewsletterSubscriptionNotFoundException();
	}
}
