<?
use Safe\DateTimeImmutable;

/**
 * @property-read string $Url
 * @property array<PollItem> $PollItems
 * @property array<PollItem> $PollItemsByWinner
 * @property-read int $VoteCount
 */
class Poll{
	use Traits\Accessor;

	public int $PollId;
	public string $Name;
	public string $UrlName;
	public string $Description;
	public DateTimeImmutable $Created;
	public ?DateTimeImmutable $Start;
	public ?DateTimeImmutable $End;

	protected string $_Url;
	/** @var array<PollItem> $_PollItems */
	protected array $_PollItems;
	/** @var array<PollItem> $_PollItemsByWinner */
	protected array $_PollItemsByWinner;
	protected int $_VoteCount;


	// *******
	// GETTERS
	// *******

	protected function GetUrl(): string{
		return $this->_Url ??= '/polls/' . $this->UrlName;
	}

	protected function GetVoteCount(): int{
		return $this->_VoteCount ??= Db::QueryInt('
							SELECT count(*)
							from PollVotes pv
							inner join PollItems pi using (PollItemId)
							where pi.PollId = ?
						', [$this->PollId]);
	}

	/**
	 * @return array<PollItem>
	 */
	protected function GetPollItems(): array{
		return $this->_PollItems ??= Db::Query('
							SELECT *
							from PollItems
							where PollId = ?
							order by SortOrder asc
						', [$this->PollId], PollItem::class);
	}

	/**
	 * @return array<PollItem>
	 */
	protected function GetPollItemsByWinner(): array{
		if(!isset($this->_PollItemsByWinner)){
			$this->_PollItemsByWinner = $this->PollItems;
			usort($this->_PollItemsByWinner, function(PollItem $a, PollItem $b){ return $a->VoteCount <=> $b->VoteCount; });

			$this->_PollItemsByWinner = array_reverse($this->_PollItemsByWinner);
		}

		return $this->_PollItemsByWinner;
	}


	// *******
	// METHODS
	// *******

	public function IsActive(): bool{
		if( ($this->Start !== null && $this->Start > NOW) || ($this->End !== null && $this->End < NOW)){
			return false;
		}

		return true;
	}


	// ***********
	// ORM METHODS
	// ***********

	/**
	 * @throws Exceptions\PollNotFoundException
	 */
	public static function Get(?int $pollId): Poll{
		if($pollId === null){
			throw new Exceptions\PollNotFoundException();
		}

		$result = Db::Query('
					SELECT *
					from Polls
					where PollId = ?
				', [$pollId], Poll::class);

		return $result[0] ?? throw new Exceptions\PollNotFoundException();
	}

	/**
	 * @throws Exceptions\PollNotFoundException
	 */
	public static function GetByUrlName(?string $urlName): Poll{
		if($urlName === null){
			throw new Exceptions\PollNotFoundException();
		}

		$result = Db::Query('
					SELECT *
					from Polls
					where UrlName = ?
				', [$urlName], Poll::class);

		return $result[0] ?? throw new Exceptions\PollNotFoundException();
	}
}
