<?
use Safe\DateTimeImmutable;

/**
 * @property int $DonationCount
 * @property int $StretchDonationCount
 * @property bool $IsStretchEnabled
 * @property int $TargetDonationCount
 */
class DonationDrive{
	use Traits\Accessor;

	protected int $_DonationCount;
	protected int $_StretchDonationCount;
	protected bool $_IsStretchEnabled;
	protected int $_TargetDonationCount;

	public function __construct(public string $Name, public DateTimeImmutable $Start, public DateTimeImmutable $End, public int $BaseTargetDonationCount, public int $StretchTargetDonationCount){
	}


	// *******
	// GETTERS
	// *******

	protected function GetDonationCount(): int{
		return $this->_DonationCount ??= Db::QueryInt('
								SELECT sum(cnt)
								from
								(
									(
										# Anonymous patrons, i.e. from AOGF
										select count(*) cnt from Payments
										where
										UserId is null
										and
										(
											(IsRecurring = true and Amount >= 10 and Created >= ?)
											or
											(IsRecurring = false and Amount >= 100 and Created >= ?)
										)
									)
									union all
									(
										# All non-anonymous patrons
										select count(*) as cnt from Patrons
										where Created >= ?
									)
								) x
								', [$this->Start, $this->Start, $this->Start]);
	}

	protected function GetTargetDonationCount(): int{
		if(!isset($this->_TargetDonationCount)){
			$this->_TargetDonationCount = $this->BaseTargetDonationCount;

			if($this->DonationCount >= $this->BaseTargetDonationCount){
				$this->_TargetDonationCount = $this->_TargetDonationCount + $this->StretchTargetDonationCount;
			}
		}

		return $this->_TargetDonationCount;
	}

	protected function GetStretchDonationCount(): int{
		if(!isset($this->_StretchDonationCount)){
			$this->_StretchDonationCount = $this->DonationCount - $this->BaseTargetDonationCount;
			if($this->_StretchDonationCount < 0){
				$this->_StretchDonationCount = 0;
			}
		}

		return $this->_StretchDonationCount;
	}

	protected function GetIsStretchEnabled(): bool{
		if(!isset($this->_IsStretchEnabled)){
			$this->_IsStretchEnabled = false;

			if($this->StretchTargetDonationCount > 0 && $this->DonationCount >= $this->BaseTargetDonationCount){
				$this->_IsStretchEnabled = true;
			}
		}

		return $this->_IsStretchEnabled;
	}


	// ***********
	// ORM METHODS
	// ***********

	public static function GetByIsRunning(): ?DonationDrive{
		foreach(DONATION_DRIVE_DATES as $donationDrive){
			if(NOW > $donationDrive->Start && NOW < $donationDrive->End){
				return $donationDrive;
			}
		}

		return null;
	}
}
