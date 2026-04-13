<?
use Safe\DateTimeImmutable;

/**
 * @property ?User $User
 * @property string $ProcessorUrl
 */
class Payment{
	use Traits\Accessor;

	public int $PaymentId;
	public ?int $UserId = null;
	public DateTimeImmutable $Created;
	public Enums\PaymentProcessorType $Processor;
	public string $TransactionId;
	public float $Amount;
	public float $Fee;
	public bool $IsRecurring;
	public bool $IsMatchingDonation = false;

	protected ?User $_User = null;
	protected string $_ProcessorUrl;


	// *******
	// GETTERS
	// *******

	/**
	 * @throws Exceptions\UserNotFoundException
	 */
	protected function GetUser(): ?User{
		if(!isset($this->_User) && $this->UserId !== null){
			$this->_User = User::Get($this->UserId);
		}

		return $this->_User;
	}

	protected function GetProcessorUrl(): string{
		if(!isset($this->_ProcessorUrl)){
			switch($this->Processor){
				case Enums\PaymentProcessorType::FracturedAtlas:
					// This is not a permalink per se, because the FA permalink shows us the donor-facing receipt, without useful information like attribution, etc. However if we search by donation ID, we *do* get that information.
					$this->_ProcessorUrl = 'https://fundraising.fracturedatlas.org/admin/general_support/donations?query=' . $this->TransactionId;
					break;
				default:
					$this->_ProcessorUrl = '';
			}
		}

		return $this->_ProcessorUrl;
	}


	// *******
	// METHODS
	// *******

	/**
	 * @throws Exceptions\PaymentExistsException
	 */
	public function Create(): void{
		if($this->UserId === null){
			// Check if we have to create a new `User` in the database.

			// If the `User` isn't `null`, then check if we already have this `User` in our system.
			if($this->User !== null && $this->User->Email !== null){
				try{
					$user = User::GetByEmail($this->User->Email);
					// `User` exists, use their data.
					$user->Name = $this->User->Name;
					$this->User = $user;

					// Update their name in case we have their email (but not name) recorded from a newsletter subscription.
					Db::Query('
						UPDATE Users
						set Name = ?
						where UserId = ?
					', [$this->User->Name, $this->User->UserId]);
				}
				catch(Exceptions\UserNotFoundException){
					// User doesn't exist, create it now.
					// Don't require an email address because we might be an anonymous `User`, or a matching donation from a fund.
					try{
						$this->User->Create(requireEmail: false);
					}
					catch(Exceptions\UserExistsException | Exceptions\InvalidUserException){
						// `User` already exists, pass.
					}
				}

				$this->UserId = $this->User->UserId;
			}
		}

		try{
			$this->PaymentId = Db::QueryInt('
				INSERT into Payments (UserId, Created, Processor, TransactionId, Amount, Fee, IsRecurring, IsMatchingDonation)
				values(?,
				       ?,
				       ?,
				       ?,
				       ?,
				       ?,
				       ?,
				       ?)
				returning PaymentId
			', [$this->UserId, $this->Created, $this->Processor, $this->TransactionId, $this->Amount, $this->Fee, $this->IsRecurring, $this->IsMatchingDonation]);


			if(!$this->IsRecurring && !$this->IsMatchingDonation){
				// Add any one-time payments to any active `DonationCounter`s.
				DonationCounter::AddCountToIsActive();
			}
		}
		catch(Exceptions\DuplicateDatabaseKeyException){
			throw new Exceptions\PaymentExistsException();
		}
	}

	/**
	 * Get net payment totals grouped by month, split into the datasets used by the payments received graph.
	 *
	 * @return array{0: array<string, float>, 1: array<string, float>, 2: array<string, float>} Key `0` contains recurring net payments, key `1` contains one-time net payments from users who are or were Patrons, and key `2` contains other one-time net payments. Inner keys are month labels in `Y-m` format, and inner values are the total net payment amount for that month.
	 */
	public static function GetNetByMonth(DateTimeImmutable $from, DateTimeImmutable $to): array{
		if($from > $to){
			[$from, $to] = [$to, $from];
		}

		$queryFrom = $from->modify('first day of this month')->setTime(0, 0);
		$queryTo = $to->add(new DateInterval('P1D'))->setTime(0, 0);
		$queryToMonth = $to->modify('first day of this month')->setTime(0, 0);
		$recurringValues = [];
		$patronValues = [];
		$otherValues = [];

		// Use `SET statement max_recursive_iterations` to allow for very wide date ranges. Otherwise, MariaDB's default of 1,000 might cause the result set to end prematurely.
		$result = Db::Query('
			SET statement max_recursive_iterations = 100000 for
			with recursive Months as (
				select cast(? as date) as Month
				union all
				select cast(date_add(Month, interval 1 month) as date)
				from Months
				where Month < ?
			)
			select
				date_format(Months.Month, "%Y-%m") as Month,
				sum(case when Payments.IsRecurring = true then Payments.Amount - Payments.Fee else 0 end) as RecurringAmount,
				sum(case when Payments.IsRecurring = false and PatronUsers.UserId is not null then Payments.Amount - Payments.Fee else 0 end) as PatronAmount,
				sum(case when Payments.IsRecurring = false and PatronUsers.UserId is null then Payments.Amount - Payments.Fee else 0 end) as OtherAmount
			from Months
			left join Payments on
				Payments.Created >= Months.Month
				and Payments.Created < date_add(Months.Month, interval 1 month)
				and Payments.Created >= ?
				and Payments.Created < ?
			left join (
				select distinct UserId
				from Patrons
			) PatronUsers on Payments.UserId = PatronUsers.UserId
			group by Months.Month
			order by Months.Month
		', [$queryFrom, $queryToMonth, $from, $queryTo]);

		foreach($result as $row){
			$recurringValues[$row->Month] = $row->RecurringAmount;
			$patronValues[$row->Month] = $row->PatronAmount;
			$otherValues[$row->Month] = $row->OtherAmount;
		}

		return [$recurringValues, $patronValues, $otherValues];
	}
}
