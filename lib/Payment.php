<?

/**
 * @property User $User
 */
class Payment extends PropertiesBase{
	public $PaymentId;
	public $UserId = null;
	protected $_User = null;
	public $Created;
	public $ChannelId;
	public $TransactionId;
	public $Amount;
	public $Fee;
	public $IsRecurring;


	// *******
	// METHODS
	// *******

	public function Create(): void{
		if($this->UserId === null){
			// Check if we have to create a new user in the database

			// If the User object isn't null, then check if we already have this user in our system
			if($this->User !== null && $this->User->Email !== null){
				$result = Db::Query('SELECT * from Users where Email = ?', [$this->User->Email], 'User');

				if(sizeof($result) == 0){
					// User doesn't exist, create it now
					$this->User->Create();
				}
				else{
					// User exists, use their data
					$this->User = $result[0];
				}

				$this->UserId = $this->User->UserId;
			}
		}

		try{
			Db::Query('INSERT into Payments (UserId, Created, ChannelId, TransactionId, Amount, Fee, IsRecurring) values(?, ?, ?, ?, ?, ?, ?);', [$this->UserId, $this->Created, $this->ChannelId, $this->TransactionId, $this->Amount, $this->Fee, $this->IsRecurring]);
		}
		catch(PDOException $ex){
			if($ex->errorInfo[1] == 1062){
				// Duplicate unique key; transction ID already exists
				throw new Exceptions\PaymentExistsException();
			}
			else{
				throw $ex;
			}
		}

		$this->PaymentId = Db::GetLastInsertedId();
	}
}
