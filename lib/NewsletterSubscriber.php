<?
use Safe\DateTime;
use Ramsey\Uuid\Uuid;

/**
 * @property string $Url
 */
class NewsletterSubscriber extends PropertiesBase{
	public $NewsletterSubscriberId;
	public $Uuid;
	public $Email;
	public $FirstName;
	public $LastName;
	public $IsConfirmed = false;
	public $IsSubscribedToSummary = true;
	public $IsSubscribedToNewsletter = true;
	public $Created;
	protected $_Url = null;

	// *******
	// GETTERS
	// *******

	protected function GetUrl(): string{
		if($this->_Url === null){
			$this->_Url = SITE_URL . '/newsletter/subscribers/' . $this->Uuid;
		}

		return $this->_Url;
	}


	// *******
	// METHODS
	// *******

	public function Create(): void{
		$this->Validate();

		$uuid = Uuid::uuid4();
		$this->Uuid = $uuid->toString();
		$this->Created = new DateTime();

		try{
			Db::Query('INSERT into NewsletterSubscribers (Email, Uuid, FirstName, LastName, IsConfirmed, IsSubscribedToNewsletter, IsSubscribedToSummary, Created) values (?, ?, ?, ?, ?, ?, ?, ?);', [$this->Email, $this->Uuid, $this->FirstName, $this->LastName, false, $this->IsSubscribedToNewsletter, $this->IsSubscribedToSummary, $this->Created]);
		}
		catch(PDOException $ex){
			if($ex->errorInfo[1] == 1062){
				// Duplicate unique key; email already in use
				throw new Exceptions\NewsletterSubscriberExistsException();
			}
			else{
				throw $ex;
			}
		}

		$this->NewsletterSubscriberId = Db::GetLastInsertedId();

		// Send the double opt-in confirmation email
		$em = new Email(true);
		$em->PostmarkStream = EMAIL_POSTMARK_STREAM_BROADCAST;
		$em->To = $this->Email;
		$em->Subject = 'Action required: confirm your newsletter subscription';
		$em->Body = Template::EmailNewsletterConfirmation(['subscriber' => $this, 'isSubscribedToSummary' => $this->IsSubscribedToSummary, 'isSubscribedToNewsletter' => $this->IsSubscribedToNewsletter]);
		$em->TextBody = Template::EmailNewsletterConfirmationText(['subscriber' => $this, 'isSubscribedToSummary' => $this->IsSubscribedToSummary, 'isSubscribedToNewsletter' => $this->IsSubscribedToNewsletter]);
		$em->Send();
	}

	public function Confirm(): void{
		Db::Query('UPDATE NewsletterSubscribers set IsConfirmed = true where NewsletterSubscriberId = ?;', [$this->NewsletterSubscriberId]);
	}

	public function Delete(): void{
		Db::Query('DELETE from NewsletterSubscribers where  NewsletterSubscriberId = ?;', [$this->NewsletterSubscriberId]);
	}

	public function Validate(): void{
		$error = new Exceptions\ValidationException();

		if($this->Email == '' || !filter_var($this->Email, FILTER_VALIDATE_EMAIL)){
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

	public static function Get(string $uuid): NewsletterSubscriber{
		$subscribers = Db::Query('SELECT * from NewsletterSubscribers where Uuid = ?;', [$uuid], 'NewsletterSubscriber');

		if(sizeof($subscribers) == 0){
			throw new Exceptions\InvalidNewsletterSubscriberException();
		}

		return $subscribers[0];
	}
}
