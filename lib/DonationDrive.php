<?
use Safe\DateTimeImmutable;

/**
 * A `DonationDrive` is a drive hosted by SE, in which we have a target number of donations and an end date.
 */
class DonationDrive{
	public int $DonationDriveId;
	public Enums\DonationTargetType $TargetType;
	public string $Name;
	public DateTimeImmutable $StartAt;
	public DateTimeImmutable $EndAt;
	public int $Target = 0;
	public ?int $StretchTarget;
	public int $Count = 0;
	public DateTimeImmutable $CreatedAt;
	public DateTimeImmutable $UpdatedAt;

	/** The base target plus the stretch target after the base target is met. */
	public private(set) int $CurrentTarget{
		get{
			if(!isset($this->CurrentTarget)){
				$this->CurrentTarget = $this->Target;

				if($this->Count >= $this->Target){
					$this->CurrentTarget += $this->StretchTarget;
				}
			}

			return $this->CurrentTarget;
		}
	}

	/** The number of donations counted toward the stretch target. */
	public private(set) int $StretchCount{
		get{
			if(!isset($this->StretchCount)){
				$this->StretchCount = $this->Count - $this->Target;
				if($this->StretchCount < 0){
					$this->StretchCount = 0;
				}
			}

			return $this->StretchCount;
		}
	}

	/** Whether the stretch target is active. */
	public private(set) bool $IsStretchEnabled{
		get{
			if(!isset($this->IsStretchEnabled)){
				$this->IsStretchEnabled = false;

				if(isset($this->StretchTarget) && $this->StretchTarget > 0 && $this->Count >= $this->Target){
					$this->IsStretchEnabled = true;
				}
			}

			return $this->IsStretchEnabled;
		}
	}

	/**
	 * Recalculating the count can be done by:
	 *
	 * ````php
	 * Db::QueryInt('
		select sum(cnt)
		from
		(
			(
				# Anonymous Patrons, i.e. from AOGF.
				select count(*) cnt from Payments
				where
				UserId is null
				and
				(
					#(IsRecurring = true and Amount >= ? and CreatedAt >= ?)
					#or
					(IsRecurring = false and Amount >= ? and CreatedAt >= ?)
				)
			)
			union all
			(
				# All non-anonymous *new* Patrons.
				select count(*) as cnt
				from
				(
					select CreatedAt
					from Patrons
					where
					UserId is not null
					group by UserId
					having count(UserId) = 1
				) x
				where
				CreatedAt >= ?
			)
		) y
		', [PATRONS_CIRCLE_YEARLY_COST, $startDateUtc, $startDateUtc]);
		````
	 */


	// ***********
	// ORM METHODS
	// ***********

	public static function AddCountToIsActive(Enums\DonationTargetType $targetType): void{
		Db::Query('update DonationDrives set Count = Count + 1 where utc_timestamp() > StartAt and utc_timestamp() < EndAt and TargetType = ?', [$targetType]);
	}

	public static function GetByIsActive(): ?DonationDrive{
		return Db::Query('select * from DonationDrives where utc_timestamp() > StartAt and utc_timestamp() < EndAt and Count < Target + StretchTarget', [], DonationDrive::class)[0] ?? null;
	}
}
