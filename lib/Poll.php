<?
use Safe\DateTimeImmutable;
use function Safe\usort;

/**
 * @property ?array<PollItem> $_PollItems
 * @property ?array<PollItem> $_PollItemsByWinner
 * @property string $Url
 * @property array<PollItem> $PollItems
 * @property array<PollItem> $PollItemsByWinner
 * @property int $VoteCount
 */
class Poll extends Accessor{
	public int $PollId;
	public string $Name;
	public string $UrlName;
	public string $Description;
	public DateTimeImmutable $Created;
	public DateTimeImmutable $Start;
	public DateTimeImmutable $End;
	protected ?string $_Url = null;
	protected $_PollItems = null;
	protected $_PollItemsByWinner = null;
	protected ?int $_VoteCount = null;


	// *******
	// GETTERS
	// *******

	protected function GetUrl(): string{
		if($this->_Url === null){
			$this->_Url = '/polls/' . $this->UrlName;
		}

		return $this->_Url;
	}

	protected function GetVoteCount(): int{
		if($this->_VoteCount === null){
			$this->_VoteCount = Db::QueryInt('
							SELECT count(*)
							from PollVotes pv
							inner join PollItems pi using (PollItemId)
							where pi.PollId = ?
						', [$this->PollId]);
		}

		return $this->_VoteCount;
	}

	/**
	 * @return array<PollItem>
	 */
	protected function GetPollItems(): array{
		if($this->_PollItems === null){
			$this->_PollItems = Db::Query('
							SELECT *
							from PollItems
							where PollId = ?
							order by SortOrder asc
						', [$this->PollId], 'PollItem');
		}

		return $this->_PollItems;
	}

	/**
	 * @return array<PollItem>
	 */
	protected function GetPollItemsByWinner(): array{
		if($this->_PollItemsByWinner === null){
			$this->_PollItemsByWinner = $this->PollItems;
			usort($this->_PollItemsByWinner, function(PollItem $a, PollItem $b){ return $a->VoteCount <=> $b->VoteCount; });

			$this->_PollItemsByWinner = array_reverse($this->_PollItemsByWinner ?? []);
		}

		return $this->_PollItemsByWinner;
	}


	// *******
	// METHODS
	// *******

	public function IsActive(): bool{
		$now = new DateTimeImmutable();
		if( ($this->Start !== null && $this->Start > $now) || ($this->End !== null && $this->End < $now)){
			return false;
		}

		return true;
	}


	// ***********
	// ORM METHODS
	// ***********

	public static function Get(?int $pollId): Poll{
		if($pollId === null){
			throw new Exceptions\PollNotFoundException();
		}

		$result = Db::Query('
					SELECT *
					from Polls
					where PollId = ?
				', [$pollId], 'Poll');

		if(sizeof($result) == 0){
			throw new Exceptions\PollNotFoundException();
		}

		return $result[0];
	}

	public static function GetByUrlName(?string $urlName): Poll{
		if($urlName === null){
			throw new Exceptions\PollNotFoundException();
		}

		$result = Db::Query('
					SELECT *
					from Polls
					where UrlName = ?
				', [$urlName], 'Poll');

		if(sizeof($result) == 0){
			throw new Exceptions\PollNotFoundException();
		}

		return $result[0];
	}
}
