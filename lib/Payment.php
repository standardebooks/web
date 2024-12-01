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

			// If the `User` isn't **null**, then check if we already have this user in our system.
			if($this->User !== null && $this->User->Email !== null){
				try{
					$user = User::GetByEmail($this->User->Email);

					// `User` exists, use their data
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

					try{
						$this->User->Create();
					}
					catch(Exceptions\UserExistsException | Exceptions\InvalidUserException){
						// `User` already exists, pass.
					}
				}

				$this->UserId = $this->User->UserId;
			}
		}

		try{
			Db::Query('
				INSERT into Payments (UserId, Created, Processor, TransactionId, Amount, Fee, IsRecurring, IsMatchingDonation)
				values(?,
				       ?,
				       ?,
				       ?,
				       ?,
				       ?,
				       ?,
				       ?)
			', [$this->UserId, $this->Created, $this->Processor, $this->TransactionId, $this->Amount, $this->Fee, $this->IsRecurring, $this->IsMatchingDonation]);
		}
		catch(Exceptions\DuplicateDatabaseKeyException){
			throw new Exceptions\PaymentExistsException();
		}

		$this->PaymentId = Db::GetLastInsertedId();
	}
}
