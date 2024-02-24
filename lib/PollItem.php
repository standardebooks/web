<?
/**
 * @property int $VoteCount
 * @property Poll $Poll
 */
class PollItem extends Accessor{
	public int $PollItemId;
	public int $PollId;
	public string $Name;
	public string $Description;
	protected ?int $_VoteCount = null;
	protected ?Poll $_Poll = null;


	// *******
	// GETTERS
	// *******

	protected function GetVoteCount(): int{
		if($this->_VoteCount === null){
			$this->_VoteCount = Db::QueryInt('
							SELECT count(*)
							from PollVotes pv
							inner join PollItems pi using (PollItemId)
							where pi.PollItemId = ?
						', [$this->PollItemId]);
		}

		return $this->_VoteCount;
	}


	// ***********
	// ORM METHODS
	// ***********

	public static function Get(?int $pollItemId): PollItem{
		if($pollItemId === null ){
			throw new Exceptions\PollItemNotFoundException();
		}

		$result = Db::Query('
					SELECT *
					from PollItems
					where PollItemId = ?
				', [$pollItemId], 'PollItem');

		if(sizeof($result) == 0){
			throw new Exceptions\PollItemNotFoundException();
		}

		return $result[0];
	}
}
