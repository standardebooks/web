<?
use Safe\DateTime;

/**
 * @property User $User
 * @property PollItem $PollItem
 * @property string $Url
 */
class PollVote extends PropertiesBase{
	public int $UserId;
	public DateTime $Created;
	public ?int $PollItemId = null;
	protected ?User $_User = null;
	protected ?PollItem $_PollItem = null;
	protected ?string $_Url = null;


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

		if($this->User === null){
			$error->Add(new Exceptions\UserNotFoundException());
		}

		if($this->PollItemId === null){
			$error->Add(new Exceptions\PollItemRequiredException());
		}
		else{
			if($this->PollItem === null){
				$error->Add(new Exceptions\PollNotFoundException());
			}
			else{
				if($this->PollItem->Poll === null){
					$error->Add(new Exceptions\PollNotFoundException());
				}
				else{
					if(!$this->PollItem->Poll->IsActive()){
						$error->Add(new Exceptions\PollClosedException());
					}
				}
			}
		}

		if(!$error->HasExceptions){
			// Basic sanity checks done, now check if we've already voted
			// in this poll

			// Do we already have a vote for this poll, from this user?
			try{
				$vote = PollVote::Get($this->PollItem->Poll->UrlName, $this->UserId);
				$error->Add(new Exceptions\PollVoteExistsException($vote));
			}
			catch(Exceptions\PollVoteNotFoundException){
				// User hasn't voted yet, carry on
			}

			if(!$this->User->Benefits->CanVote){
				$error->Add(new Exceptions\InvalidPatronException());
			}
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	public function Create(?string $email = null): void{
		if($email !== null){
			try{
				$this->User = User::GetByEmail($email);
				$this->UserId = $this->User->UserId;
			}
			catch(Exceptions\UserNotFoundException){
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
		Db::Query('
			INSERT into PollVotes (UserId, PollItemId, Created)
			values (?,
			        ?,
			        ?)
		', [$this->UserId, $this->PollItemId, $this->Created]);
	}

	public static function Get(?string $pollUrlName, ?int $userId): PollVote{
		if($pollUrlName === null || $userId === null){
			throw new Exceptions\PollVoteNotFoundException();
		}

		$result = Db::Query('
					SELECT pv.*
					from PollVotes pv
					inner join
					    (select pi.PollItemId
					     from PollItems pi
					     inner join Polls p using (PollId)
					     where p.UrlName = ? ) x using (PollItemId)
					where pv.UserId = ?
				', [$pollUrlName, $userId], 'PollVote');

		if(sizeof($result) == 0){
			throw new Exceptions\PollVoteNotFoundException();
		}

		return $result[0];
	}
}
