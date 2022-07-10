<?
use Safe\DateTime;

/**
 * @property User $User
 */
class Patron extends PropertiesBase{
	public $UserId = null;
	protected $_User = null;
	public $IsAnonymous;
	public $AlternateName;
	public $IsSubscribedToEmails;
	public $Created = null;
	public $Ended = null;


	// *******
	// METHODS
	// *******

	public function Create(): void{
		$this->Created = new DateTime();
		Db::Query('INSERT into Patrons (Created, UserId, IsAnonymous, AlternateName, IsSubscribedToEmails) values(?, ?, ?, ?, ?);', [$this->Created, $this->UserId, $this->IsAnonymous, $this->AlternateName, $this->IsSubscribedToEmails]);


		Db::Query('INSERT into Benefits (UserId, CanVote, CanAccessFeeds, CanBulkDownload) values (?, true, true, true) on duplicate key update CanVote = true, CanAccessFeeds = true, CanBulkDownload = true', [$this->UserId]);

		// If this is a patron for the first time, send the first-time patron email.
		// Otherwise, send the returning patron email.
		$isReturning = Db::QueryInt('SELECT count(*) from Patrons where UserId = ?', [$this->UserId]) > 1;

		$this->SendWelcomeEmail($isReturning);
	}

	private function SendWelcomeEmail(bool $isReturning): void{
		if($this->User !== null){
			$em = new Email();
			$em->To = $this->User->Email;
			$em->ToName = $this->User->Name;
			$em->From = EDITOR_IN_CHIEF_EMAIL_ADDRESS;
			$em->FromName = EDITOR_IN_CHIEF_NAME;
			$em->Subject = 'Thank you for supporting Standard Ebooks!';
			$em->Body = Template::EmailPatronsCircleWelcome(['isAnonymous' => $this->IsAnonymous, 'isReturning' => $isReturning]);
			$em->TextBody = Template::EmailPatronsCircleWelcomeText(['isAnonymous' => $this->IsAnonymous, 'isReturning' => $isReturning]);
			$em->Send();

			$em = new Email();
			$em->To = ADMIN_EMAIL_ADDRESS;
			$em->From = ADMIN_EMAIL_ADDRESS;
			$em->Subject = 'New Patrons Circle member';
			$em->Body = Template::EmailAdminNewPatron(['patron' => $this, 'payment' => $this->User->Payments[0]]);
			$em->TextBody = Template::EmailAdminNewPatronText(['patron' => $this, 'payment' => $this->User->Payments[0]]);;
			$em->Send();
		}
	}


	// ***********
	// ORM METHODS
	// ***********

	public static function Get(?int $userId): Patron{
		$result = Db::Query('SELECT * from Patrons where UserId = ?', [$userId], 'Patron');

		if(sizeof($result) == 0){
			throw new Exceptions\InvalidPatronException();
		}

		return $result[0];
	}

	public static function GetByEmail(?string $email): Patron{
		$result = Db::Query('SELECT p.* from Patrons p inner join Users u using(UserId) where u.Email = ?', [$email], 'Patron');

		if(sizeof($result) == 0){
			throw new Exceptions\InvalidPatronException();
		}

		return $result[0];
	}
}
