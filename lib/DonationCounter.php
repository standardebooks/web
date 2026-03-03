<?
use Safe\DateTimeImmutable;

/**
 * A `DonationCounter` is a drive in which an entity (usually Fractured Atlas) offers a drawing to win a matching amount based on how many one-time donations are made in a timespan.
 */
class DonationCounter{
	public int $DonationCounterId;
	public string $Name;
	public DateTimeImmutable $Start;
	public DateTimeImmutable $End;
	public int $MatchAmount = 0;
	public int $Count = 0;
	public ?string $ExternalUrl;
	public DateTimeImmutable $Created;
	public DateTimeImmutable $Updated;


	// ***********
	// ORM METHODS
	// ***********

	public static function AddCountToIsActive(): void{
		Db::Query('UPDATE DonationCounters set Count = Count + 1 where utc_timestamp() > Start and utc_timestamp() < End', []);
	}

	public static function GetByIsActive(): ?DonationCounter{
		return Db::Query('SELECT * from DonationCounters where utc_timestamp() > Start and utc_timestamp() < End', [], DonationCounter::class)[0] ?? null;
	}
}
