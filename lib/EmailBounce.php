<?
use Safe\DateTimeImmutable;

class EmailBounce{
	public ?int $UserId;
	public DateTimeImmutable $Timestamp;
	public string $Email;
	public Enums\EmailBounceType $Type;
	public bool $IsActive = true;
	public Enums\EmailProviderType $Source;

	/**
	 * @throws Exceptions\EmailBounceNotFoundException If the `EmailBounce` can't be found.
	 */
	public static function GetActiveByUserId(int $userId): EmailBounce{
		return Db::Query('SELECT * from EmailBounces where UserId = ? and IsActive = true', [$userId], EmailBounce::class)[0] ?? throw new Exceptions\EmailBounceNotFoundException();
	}

	public function Create(): void{
		$this->Timestamp = NOW;

		Db::Query('INSERT into EmailBounces (Email, UserId, Timestamp, Type, IsActive, Source) values (?, ?, ?, ?, ?, ?)', [$this->Email, $this->UserId, $this->Timestamp, $this->Type, $this->IsActive, $this->Source]);

		if($this->UserId !== null){
			Db::Query('UPDATE Users set CanReceiveEmail = false where UserId = ?', [$this->UserId]);
			Db::Query('DELETE from NewsletterSubscriptions where UserId = ?', [$this->UserId]);
		}

		// Delete any queued email for this address.
		// `To` must be escaped because it's an SQL keyword.
		Db::Query('DELETE from QueuedEmailMessages where `To` = ?', [$this->Email]);
	}
}
