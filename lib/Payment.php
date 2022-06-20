<?

class Payment extends PropertiesBase{
	public $PaymentId;
	protected $User = null;
	public $UserId = null;
	public $Timestamp;
	public $ChannelId;
	public $TransactionId;
	public $Amount;
	public $Fee;
	public $IsRecurring;

	protected function GetUser(): ?User{
		if($this->User === null && $this->UserId !== null){
			$this->User = User::Get($this->UserId);
		}

		return $this->User;
	}

	public function Create(): void{
		if($this->UserId === null){
			// Check if we have to create a new user in the database

			// If the User object isn't null, then check if we already have this user in our system
			if($this->User !== null && $this->User->Email !== null){
				$result = Db::Query('select * from Users where Email = ?', [$this->User->Email], 'User');

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
			Db::Query('insert into Payments (UserId, Timestamp, ChannelId, TransactionId, Amount, Fee, IsRecurring) values(?, ?, ?, ?, ?, ?, ?);', [$this->UserId, $this->Timestamp, $this->ChannelId, $this->TransactionId, $this->Amount, $this->Fee, $this->IsRecurring]);
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
