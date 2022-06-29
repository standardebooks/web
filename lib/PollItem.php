<?
class PollItem extends PropertiesBase{
	public $PollItemId;
	public $PollId;
	public $Name;
	public $Description;
	protected $VoteCount = null;
	protected $Poll = null;

	protected function GetVoteCount(): int{
		if($this->VoteCount === null){
			$this->VoteCount = (Db::Query('select count(*) as VoteCount from Votes v inner join PollItems pi on v.PollItemId = pi.PollItemId where pi.PollItemId = ?', [$this->PollItemId]))[0]->VoteCount;
		}

		return $this->VoteCount;
	}

	public static function Get(?int $pollItemId): PollItem{
		$result = Db::Query('SELECT * from PollItems where PollItemId = ?', [$pollItemId], 'PollItem');

		if(sizeof($result) == 0){
			throw new Exceptions\InvalidPollItemException();
		}

		return $result[0];
	}
}
