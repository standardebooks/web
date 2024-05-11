<?
use Safe\DateTimeImmutable;

/**
 * @property User $User
 */
class Payment{
	use Traits\Accessor;

	public int $PaymentId;
	public ?int $UserId = null;
	public DateTimeImmutable $Created;
	public PaymentProcessor $Processor;
	public string $TransactionId;
	public float $Amount;
	public float $Fee;
	public bool $IsRecurring;
	public bool $IsMatchingDonation = false;

	protected ?User $_User = null;


	// *******
	// METHODS
	// *******

	/**
	 * @throws Exceptions\PaymentExistsException
	 */
	public function Create(): void{
		if($this->UserId === null){
			// Check if we have to create a new user in the database

			// If the User object isn't null, then check if we already have this user in our system
			if($this->User !== null && $this->User->Email !== null){
				try{
					$user = User::GetByEmail($this->User->Email);

					// User exists, use their data
					$user->Name = $this->User->Name;
					$this->User = $user;

					// Update their name in case we have their email (but not name) recorded from a newsletter subscription
					Db::Query('
						UPDATE Users
						set Name = ?
						where UserId = ?
					', [$this->User->Name, $this->User->UserId]);
				}
				catch(Exceptions\UserNotFoundException){
					// User doesn't exist, create it now

					try{
						$this->User->Create();
					}
					catch(Exceptions\UserExistsException){
						// User already exists, pass
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
