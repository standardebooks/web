<?
use Safe\DateTime;

class Patron extends PropertiesBase{
	protected $User = null;
	public $UserId = null;
	public $IsAnonymous;
	public $AlternateName;
	public $IsSubscribedToEmail;
	public $Timestamp = null;
	public $DeactivatedTimestamp = null;

	public static function Get(int $userId): ?Patron{
		$result = Db::Query('select * from Patrons where UserId = ?', [$userId], 'Patron');

		return $result[0] ?? null;
	}

	protected function GetUser(): ?User{
		if($this->User === null && $this->UserId !== null){
			$this->User = User::Get($this->UserId);
		}

		return $this->User;
	}

	public function Create(bool $sendEmail = true): void{
		Db::Query('insert into Patrons (Timestamp, UserId, IsAnonymous, AlternateName, IsSubscribedToEmail) values(utc_timestamp(), ?, ?, ?, ?);', [$this->UserId, $this->IsAnonymous, $this->AlternateName, $this->IsSubscribedToEmail]);

		if($sendEmail){
			$this->SendWelcomeEmail();
		}
	}

	public function Reactivate(bool $sendEmail = true): void{
		Db::Query('update Patrons set Timestamp = utc_timestamp(), DeactivatedTimestamp = null, IsAnonymous = ?, IsSubscribedToEmail = ?, AlternateName = ? where UserId = ?;', [$this->IsAnonymous, $this->IsSubscribedToEmail, $this->AlternateName, $this->UserId]);
		$this->Timestamp = new DateTime();
		$this->DeactivatedTimestamp = null;

		if($sendEmail){
			$this->SendWelcomeEmail();
		}
	}

	private function SendWelcomeEmail(): void{
		$this->GetUser();
		if($this->User !== null){
			$em = new Email();
			$em->To = $this->User->Email;
			$em->From = EDITOR_IN_CHIEF_EMAIL_ADDRESS;
			$em->Subject = 'Thank you for supporting Standard Ebooks!';
			$em->Body = Template::EmailPatronsCircleWelcome(['isAnonymous' => $this->IsAnonymous]);
			$em->TextBody = Template::EmailPatronsCircleWelcomeText(['isAnonymous' => $this->IsAnonymous]);
			//$em->Send();
		}
	}
}
