<?
use Safe\DateTime;

/**
 * @property User $User
 * @property string $Url
 */
class NewsletterSubscription extends PropertiesBase{
	public bool $IsConfirmed = false;
	public bool $IsSubscribedToSummary;
	public bool $IsSubscribedToNewsletter;
	public int $UserId;
	public DateTime $Created;
	protected $_User;
	protected $_Url = null;

	// *******
	// GETTERS
	// *******

	protected function GetUrl(): string{
		if($this->_Url === null){
			$this->_Url = '/newsletter/subscriptions/' . $this->User->Uuid;
		}

		return $this->_Url;
	}


	// *******
	// METHODS
	// *******

	public function Create(): void{
		$this->Validate();

		// Do we need to create a user?
		try{
			$this->User = User::GetByEmail($this->User->Email);
		}
		catch(Exceptions\InvalidUserException){
			// User doesn't exist, create the user
			$this->User->Create();
		}

		$this->UserId = $this->User->UserId;
		$this->Created = new DateTime();

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
		catch(PDOException $ex){
			if(($ex->errorInfo[1] ?? 0) == 1062){
				// Duplicate unique key; email already in use
				throw new Exceptions\NewsletterSubscriptionExistsException();
			}
			else{
				throw $ex;
			}
		}

		// Send the double opt-in confirmation email
		$this->SendConfirmationEmail();
	}

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

	public function Validate(): void{
		$error = new Exceptions\ValidationException();

		if($this->User === null || $this->User->Email == '' || !filter_var($this->User->Email, FILTER_VALIDATE_EMAIL)){
			$error->Add(new Exceptions\InvalidEmailException());
		}

		if(!$this->IsSubscribedToSummary && !$this->IsSubscribedToNewsletter){
			$error->Add(new Exceptions\NewsletterRequiredException());
		}

		if($error->HasExceptions){
			throw $error;
		}
	}


	// ***********
	// ORM METHODS
	// ***********

	public static function Get(string $uuid): NewsletterSubscription{
		$result = Db::Query('
				SELECT ns.*
				from NewsletterSubscriptions ns
				inner join Users u using(UserId)
				where u.Uuid = ?
			', [$uuid], 'NewsletterSubscription');

		if(sizeof($result) == 0){
			throw new Exceptions\InvalidNewsletterSubscriptionException();
		}

		return $result[0];
	}
}
