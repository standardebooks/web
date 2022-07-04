<?
use Safe\DateTime;

/**
 * @property User $User
 * @property PollItem $PollItem
 * @property string $Url
 */
class Vote extends PropertiesBase{
	public $VoteId;
	public $UserId;
	protected $_User = null;
	public $Created;
	public $PollItemId;
	protected $_PollItem = null;
	protected $_Url = null;


	// *******
	// GETTERS
	// *******

	protected function GetUrl(): string{
		if($this->_Url === null){
			$this->_Url = $this->PollItem->Poll->Url . '/votes/' . $this->UserId;
		}

		return $this->_Url;
	}


	// *******
	// METHODS
	// *******

	protected function Validate(): void{
		$error = new Exceptions\ValidationException();

		if($this->UserId === null){
			$error->Add(new Exceptions\InvalidPatronException());
		}

		if($this->PollItemId === null){
			$error->Add(new Exceptions\PollItemRequiredException());
		}
		else{
			if($this->PollItem === null){
				$error->Add(new Exceptions\InvalidPollException());
			}
		}

		if(!$this->PollItem->Poll->IsActive()){
			$error->Add(new Exceptions\PollClosedException());
		}

		if(!$error->HasExceptions){
			// Basic sanity checks done, now check if we've already voted
			// in this poll

			if($this->User === null){
				$error->Add(new Exceptions\InvalidPatronException());
			}
			else{
				// Do we already have a vote for this poll, from this user?
				if(Db::QueryInt('
					SELECT count(*) from Votes v inner join
					(select PollItemId from PollItems pi inner join Polls p on pi.PollId = p.PollId) x
					on v.PollItemId = x.PollItemId where v.UserId = ?', [$this->UserId]) > 0){
					$error->Add(new Exceptions\VoteExistsException());
				}
			}
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	public function Create(?string $email = null): void{
		if($email !== null){
			try{
				$patron = Patron::GetByEmail($email);
				$this->UserId = $patron->UserId;
				$this->User = $patron->User;
			}
			catch(Exceptions\InvalidPatronException $ex){
				// Can't validate patron email - do nothing for now,
				// this will be caught later when we validate the vote during creation.
				// Save the email in the User object in case we want it later,
				// for example prefilling the 'create' form after an error is returned.
				$this->User = new User();
				$this->User->Email = $email;
			}
		}

		$this->Validate();
		$this->Created = new DateTime();
		Db::Query('INSERT into Votes (UserId, PollItemId, Created) values (?, ?, ?)', [$this->UserId, $this->PollItemId, $this->Created]);

		$this->VoteId = Db::GetLastInsertedId();
	}

	public static function Get(?string $pollUrlName, ?int $userId): ?Vote{
		if($pollUrlName === null || $userId === null){
			vdd('nn');
			return null;
		}

		$result = Db::Query('SELECT v.* from Votes v inner join
					(select pi.PollItemId from PollItems pi inner join Polls p on pi.PollId = p.PollID
						where p.UrlName = ?
					) x on v.PollItemId = x.PollItemId where v.UserId = ?', [$pollUrlName, $userId], 'Vote');

		if(sizeof($result) == 0){
			throw new Exceptions\InvalidVoteException();
		}

		return $result[0];
	}
}
