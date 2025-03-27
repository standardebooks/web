<?
/**
 * @property-read int $VoteCount
 * @property Poll $Poll
 */
class PollItem{
	use Traits\Accessor;

	public int $PollItemId;
	public int $PollId;
	public string $Name;
	public ?string $Description;

	protected int $_VoteCount;
	protected Poll $_Poll;


	// *******
	// GETTERS
	// *******

	protected function GetVoteCount(): int{
		return $this->_VoteCount ??= Db::QueryInt('
							SELECT count(*)
							from PollVotes pv
							inner join PollItems pi using (PollItemId)
							where pi.PollItemId = ?
						', [$this->PollItemId]);
	}


	// ***********
	// ORM METHODS
	// ***********

	/**
	 * @throws Exceptions\PollItemNotFoundException
	 */
	public static function Get(?int $pollItemId): PollItem{
		if($pollItemId === null ){
			throw new Exceptions\PollItemNotFoundException();
		}

		$result = Db::Query('
					SELECT *
					from PollItems
					where PollItemId = ?
				', [$pollItemId], PollItem::class);

		return $result[0] ?? throw new Exceptions\PollItemNotFoundException();
	}
}
