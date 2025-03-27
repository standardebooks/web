<?
use Safe\DateTimeImmutable;

/**
 * @property User $User
 * @property PollItem $PollItem
 * @property-read string $Url
 */
class PollVote{
	use Traits\Accessor;
	use Traits\PropertyFromHttp;

	public int $UserId;
	public DateTimeImmutable $Created;
	public int $PollItemId;

	protected User $_User;
	protected PollItem $_PollItem;
	protected string $_Url;


	// *******
	// GETTERS
	// *******

	protected function GetUrl(): string{
		return $this->_Url ??= $this->PollItem->Poll->Url . '/votes/' . $this->UserId;
	}


	// *******
	// METHODS
	// *******

	/**
	 * @throws Exceptions\InvalidPollVoteException
	 */
	protected function Validate(): void{
		$error = new Exceptions\InvalidPollVoteException();

		if(!isset($this->UserId)){
			$error->Add(new Exceptions\UserNotFoundException());
		}
		else{
			try{
				// Attempt to get the `User`.
				User::Get($this->UserId);
			}
			catch(Exceptions\UserNotFoundException $ex){
				$error->Add($ex);
			}
		}

		if(!isset($this->PollItemId)){
			$error->Add(new Exceptions\PollItemRequiredException());
		}
		else{
			try{
				/**
				 * @throws Exceptions\PollItemNotFoundException
				 * @throws Exceptions\PollNotFoundException
				 */
				if(!$this->PollItem->Poll->IsActive()){
					$error->Add(new Exceptions\PollClosedException());
				}
			}
			catch(Exceptions\PollItemNotFoundException | Exceptions\PollNotFoundException){
				$error->Add(new Exceptions\PollNotFoundException());
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
				$error->Add(new Exceptions\InvalidPermissionsException());
			}
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	/**
	 * @throws Exceptions\InvalidPollVoteException
	 */
	public function Create(?string $email = null): void{
		if($email !== null){
			try{
				$this->User = User::GetByEmail($email);
				$this->UserId = $this->User->UserId;
			}
			catch(Exceptions\UserNotFoundException){
				// Can't validate patron email - do nothing for now, this will be caught later when we validate the vote during creation.
				// Save the email in the User object in case we want it later, for example prefilling the 'create' form after an error is returned.
				$this->User = new User();
				$this->User->Email = $email;
			}
		}

		$this->Validate();
		Db::Query('
			INSERT into PollVotes (UserId, PollItemId)
			values (?,
			        ?)
		', [$this->UserId, $this->PollItemId]);
	}

	/**
	 * @throws Exceptions\PollVoteNotFoundException
	 */
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
				', [$pollUrlName, $userId], PollVote::class);

		return $result[0] ?? throw new Exceptions\PollVoteNotFoundException();
	}

	public function FillFromHttpPost(): void{
		$this->PropertyFromHttp('PollItemId');
	}
}
