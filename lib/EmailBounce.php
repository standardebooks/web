<?
use Safe\DateTimeImmutable;

class EmailBounce{
	public ?int $UserId;
	public DateTimeImmutable $CreatedAt;
	public string $Email;
	public Enums\EmailBounceType $Type;
	public bool $IsActive = true;
	public Enums\EmailProviderType $Source;

	public function Create(): void{
		$this->CreatedAt = NOW;

		Db::Query('INSERT into EmailBounces (Email, UserId, CreatedAt, Type, IsActive, Source) values (?, ?, ?, ?, ?, ?)', [$this->Email, $this->UserId, $this->CreatedAt, $this->Type, $this->IsActive, $this->Source]);

		if($this->UserId !== null){
			Db::Query('UPDATE Users set CanReceiveEmail = false where UserId = ?', [$this->UserId]);
			Db::Query('UPDATE NewsletterSubscriptions set IsVisible = false where UserId = ?', [$this->UserId]);
		}

		// Delete any queued email for this address.
		// `To` must be escaped because it's an SQL keyword.
		Db::Query('DELETE from QueuedEmailMessages where `To` = ?', [$this->Email]);
	}
}
