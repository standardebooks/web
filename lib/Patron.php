<?
use Safe\DateTimeImmutable;

/**
 * @property User $User
 * @property ?Payment $LastPayment
 */
class Patron{
	use Traits\Accessor;

	public int $UserId;
	public bool $IsAnonymous;
	public ?string $AlternateName = null;
	public bool $IsSubscribedToEmails;
	public DateTimeImmutable $Created;
	public ?DateTimeImmutable $Ended = null;
	public ?float $BaseCost = null;
	public ?Enums\CycleType $CycleType = null;

	protected ?Payment $_LastPayment = null;
	protected User $_User;


	// *******
	// GETTERS
	// *******

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


	// *******
	// METHODS
	// *******

	public function Create(): void{
		$this->Created = NOW;
		Db::Query('
			INSERT into Patrons (Created, UserId, IsAnonymous, AlternateName, IsSubscribedToEmails, BaseCost, CycleType)
			values(?,
			       ?,
			       ?,
			       ?,
			       ?,
			       ?,
			       ?)
		', [$this->Created, $this->UserId, $this->IsAnonymous, $this->AlternateName, $this->IsSubscribedToEmails, $this->BaseCost, $this->CycleType]);

		Db::Query('
			INSERT into Benefits (UserId, CanVote, CanAccessFeeds, CanBulkDownload)
			values (?,
			        true,
			        true,
			        true) on duplicate key
			update CanVote = true,
			       CanAccessFeeds = true,
			       CanBulkDownload = true
		', [$this->UserId]);

		// If this is a patron for the first time, send the first-time patron email.
		// Otherwise, send the returning patron email.
		$isReturning = Db::QueryInt('
				SELECT count(*)
				from Patrons
				where UserId = ?
			', [$this->UserId]) > 1;

		$this->SendWelcomeEmail($isReturning);
	}

	private function SendWelcomeEmail(bool $isReturning): void{
		if($this->User !== null){
			$em = new Email();
			$em->To = $this->User->Email ?? '';
			$em->ToName = $this->User->Name ?? '';
			$em->From = EDITOR_IN_CHIEF_EMAIL_ADDRESS;
			$em->FromName = EDITOR_IN_CHIEF_NAME;
			$em->Subject = 'Thank you for supporting Standard Ebooks!';
			$em->Body = Template::EmailPatronsCircleWelcome(['isAnonymous' => $this->IsAnonymous, 'isReturning' => $isReturning]);
			$em->TextBody = Template::EmailPatronsCircleWelcomeText(['isAnonymous' => $this->IsAnonymous, 'isReturning' => $isReturning]);
			$em->Send();

			if(!$isReturning){
				$em = new Email();
				$em->To = ADMIN_EMAIL_ADDRESS;
				$em->From = ADMIN_EMAIL_ADDRESS;
				$em->Subject = 'New Patrons Circle member';
				$em->Body = Template::EmailAdminNewPatron(['patron' => $this, 'payment' => $this->User->Payments[0]]);
				$em->TextBody = Template::EmailAdminNewPatronText(['patron' => $this, 'payment' => $this->User->Payments[0]]);;
				$em->Send();
			}
		}
	}

	public function End(?int $ebooksThisYear): void{
		if($ebooksThisYear === null){
			$ebooksThisYear = Db::QueryInt('SELECT count(*) from Ebooks where EbookCreated >= ? - interval 1 year', [NOW]);
		}

		Db::Query('
				UPDATE Patrons
				set Ended = ?
				where UserId = ?
			', [NOW, $this->UserId]);

		Db::Query('
				UPDATE Benefits
				set CanAccessFeeds = false,
				    CanVote = false,
				    CanBulkDownload = false
				where UserId = ?
			', [$this->UserId]);

		// Email the patron to notify them their term has ended.
		if($this->LastPayment !== null && $this->User->Email !== null){
			$em = new Email();
			$em->From = EDITOR_IN_CHIEF_EMAIL_ADDRESS;
			$em->FromName = EDITOR_IN_CHIEF_NAME;
			$em->To = $this->User->Email;
			$em->ToName = $this->User->Name ?? '';
			$em->Subject = 'Will you continue to help us make free, beautiful digital literature?';

			if($this->CycleType == Enums\CycleType::Monthly){
				// Email recurring donors who have lapsed.
				$em->Body = Template::EmailPatronsCircleRecurringCompleted();
				$em->TextBody = Template::EmailPatronsCircleRecurringCompletedText();
			}
			else{
				// Email one time donors who have expired after one year.
				$em->Body = Template::EmailPatronsCircleCompleted(['ebooksThisYear' => $ebooksThisYear]);
				$em->TextBody = Template::EmailPatronsCircleCompletedText(['ebooksThisYear' => $ebooksThisYear]);
			}

			$em->Send();
		}
	}


	// ***********
	// ORM METHODS
	// ***********

	/**
	 * @throws Exceptions\PatronNotFoundException
	 */
	public static function Get(?int $userId): Patron{
		if($userId === null){
			throw new Exceptions\PatronNotFoundException();
		}

		$result = Db::Query('
			SELECT *
			from Patrons
			where UserId = ?
			order by Created desc
			limit 1
			', [$userId], Patron::class);

		return $result[0] ?? throw new Exceptions\PatronNotFoundException();;
	}

	/**
	 * @throws Exceptions\PatronNotFoundException
	 */
	public static function GetByEmail(?string $email): Patron{
		if($email === null){
			throw new Exceptions\PatronNotFoundException();
		}

		$result = Db::Query('
			SELECT p.*
			from Patrons p
			inner join Users u using(UserId)
			where u.Email = ?
			order by p.Created desc
			limit 1
		', [$email], Patron::class);

		return $result[0] ?? throw new Exceptions\PatronNotFoundException();
	}
}
