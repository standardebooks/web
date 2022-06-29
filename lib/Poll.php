<?
use Safe\DateTime;
use function Safe\usort;

class Poll extends PropertiesBase{
	public $PollId;
	public $Name;
	public $UrlName;
	public $Description;
	public $Created;
	public $Start;
	public $End;
	protected $Url = null;
	protected $PollItems = null;
	protected $PollItemsByWinner = null;
	protected $VoteCount = null;

	protected function GetUrl(): string{
		if($this->Url === null){
			$this->Url = '/patrons-circle/polls/' . $this->UrlName;
		}

		return $this->Url;
	}

	protected function GetVoteCount(): int{
		if($this->VoteCount === null){
			$this->VoteCount = (Db::Query('select count(*) as VoteCount from Votes v inner join PollItems pi on v.PollItemId = pi.PollItemId where pi.PollId = ?', [$this->PollId]))[0]->VoteCount;
		}

		return $this->VoteCount;
	}

	/**
	 * @return array<PollItem>
	 */
	protected function GetPollItems(): array{
		if($this->PollItems === null){
			$this->PollItems = Db::Query('SELECT * from PollItems where PollId = ? order by SortOrder asc', [$this->PollId], 'PollItem');
		}

		return $this->PollItems;
	}

	/**
	 * @return array<PollItem>
	 */
	protected function GetPollItemsByWinner(): array{
		if($this->PollItemsByWinner === null){
			$this->__get('PollItems');
			$this->PollItemsByWinner = $this->PollItems;
			usort($this->PollItemsByWinner, function(PollItem $a, PollItem $b){ return $a->VoteCount <=> $b->VoteCount; });

			$this->PollItemsByWinner = array_reverse($this->PollItemsByWinner);
		}

		return $this->PollItemsByWinner;
	}

	public function IsActive(): bool{
		$now = new DateTime();
		if( ($this->Start !== null && $this->Start > $now) || ($this->End !== null && $this->End < $now)){
			return false;
		}

		return true;
	}

	public static function Get(?int $pollId): Poll{
		$result = Db::Query('SELECT * from Polls where PollId = ?', [$pollId], 'Poll');

		if(sizeof($result) == 0){
			throw new Exceptions\InvalidPollException();
		}

		return $result[0];
	}

	public static function GetByUrlName(?string $urlName): Poll{
		$result = Db::Query('SELECT * from Polls where UrlName = ?', [$urlName], 'Poll');

		if(sizeof($result) == 0){
			throw new Exceptions\InvalidPollException();
		}

		return $result[0];
	}
}
