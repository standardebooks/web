<?
use Safe\DateTimeImmutable;

/**
 * @property-read int $StretchCount The count that is within the range of the `StretchTarget`. For example, if `Target` is `100`, `StretchTarget` is `25`, and `Count` is `115`, then `StretchCount` is `15`.
 * @property-read bool $IsStretchEnabled
 * @property-read int $CurrentTarget The current total target count, including stretch, if enabled. For example, if `Target` is `100`, `StretchTarget` is `25`, and `Count` is `80`, then `CurrentTarget` is `100`; if `Target` is `100`, `StretchTarget` is `25`, and `Count` is `115`, then `CurrentTarget` is `125`.
 */
class DonationDrive{
	use Traits\Accessor;

	public int $DonationDriveId;
	public Enums\DonationTargetType $TargetType;
	public string $Name;
	public DateTimeImmutable $Start;
	public DateTimeImmutable $End;
	public int $Target = 0;
	public ?int $StretchTarget;
	public int $Count = 0;
	public DateTimeImmutable $Created;
	public DateTimeImmutable $Updated;

	protected int $_StretchCount;
	protected bool $_IsStretchEnabled;
	protected int $_CurrentTarget;

	/**
	 * Recalculating the count can be done by:
	 *
	 * ````php
	 *  Db::QueryInt('
		SELECT sum(cnt)
		from
		(
			(
				# Anonymous Patrons, i.e. from AOGF.
				select count(*) cnt from Payments
				where
				UserId is null
				and
				(
					#(IsRecurring = true and Amount >= ? and Created >= ?)
					#or
					(IsRecurring = false and Amount >= ? and Created >= ?)
				)
			)
			union all
			(
				# All non-anonymous *new* Patrons.
				select count(*) as cnt
				from
				(
					select Created
					from Patrons
					where
					UserId is not null
					group by UserId
					having count(UserId) = 1
				) x
				where
				Created >= ?
			)
		) y
		', [PATRONS_CIRCLE_YEARLY_COST, $startDateUtc, $startDateUtc]);
		````
	 */


	// *******
	// GETTERS
	// *******

	protected function GetCurrentTarget(): int{
		if(!isset($this->_CurrentTarget)){
			$this->_CurrentTarget = $this->Target;

			if($this->Count >= $this->Target){
				$this->_CurrentTarget = $this->_CurrentTarget + $this->StretchTarget;
			}
		}

		return $this->_CurrentTarget;
	}

	protected function GetStretchCount(): int{
		if(!isset($this->_StretchCount)){
			$this->_StretchCount = $this->Count - $this->Target;
			if($this->_StretchCount < 0){
				$this->_StretchCount = 0;
			}
		}

		return $this->_StretchCount;
	}

	protected function GetIsStretchEnabled(): bool{
		if(!isset($this->_IsStretchEnabled)){
			$this->_IsStretchEnabled = false;

			if(isset($this->StretchTarget) && $this->StretchTarget > 0 && $this->Count >= $this->Target){
				$this->_IsStretchEnabled = true;
			}
		}

		return $this->_IsStretchEnabled;
	}


	// ***********
	// ORM METHODS
	// ***********

	public static function AddCountToIsActive(Enums\DonationTargetType $targetType): void{
		Db::Query('UPDATE DonationDrives set Count = Count + 1 where utc_timestamp() > Start and utc_timestamp() < End and TargetType = ?', [$targetType]);
	}

	public static function GetByIsActive(): ?DonationDrive{
		return Db::Query('SELECT * from DonationDrives where utc_timestamp() > Start and utc_timestamp() < End', [], DonationDrive::class)[0] ?? null;
	}
}
