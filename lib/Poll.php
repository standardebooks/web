<?
use Safe\DateTimeImmutable;
use function Safe\preg_match;

/**
 * @property-read string $Url
 * @property-read string $EditUrl
 * @property-read ?Markdown $Description
 * @property-write Markdown|string|null $Description
 * @property array<PollItem> $PollItems
 * @property array<PollItem> $PollItemsByWinner
 * @property-read int $VoteCount
 */
class Poll{
	use Traits\Accessor;
	use Traits\PropertyFromRequest;

	public int $PollId;
	public string $Name;
	public string $UrlName;
	public DateTimeImmutable $Created;
	public DateTimeImmutable $Start;
	public DateTimeImmutable $End;

	protected string $_Url;
	protected string $_EditUrl;
	/** @var array<PollItem> $_PollItems */
	protected array $_PollItems;
	/** @var array<PollItem> $_PollItemsByWinner */
	protected array $_PollItemsByWinner;
	protected int $_VoteCount;
	protected ?Markdown $_Description;


	// *******
	// GETTERS
	// *******

	protected function GetUrl(): string{
		return $this->_Url ??= '/polls/' . $this->UrlName;
	}

	protected function GetEditUrl(): string{
		return $this->_EditUrl ??= $this->Url . '/edit';
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
			usort($this->_PollItemsByWinner, function(PollItem $a, PollItem $b): int{
				$voteComparison = $b->VoteCount <=> $a->VoteCount;
				if($voteComparison != 0){
					return $voteComparison;
				}

				return $a->SortOrder <=> $b->SortOrder;
			});
		}

		return $this->_PollItemsByWinner;
	}

	protected function SetDescription(string|Markdown|null $string): void{
		if($string === null){
			$this->_Description = null;
		}
		else{
			$this->_Description = new Markdown($string);
		}
	}

	// *******
	// METHODS
	// *******

	/**
	 * Return whether this poll is currently open for voting.
	 */
	public function IsActive(): bool{
		if($this->Start > NOW || $this->End <= NOW){
			return false;
		}

		return true;
	}

	/**
	 * Validate and normalize this `Poll` before it is created or saved.
	 *
	 * @throws Exceptions\PollInvalidException If this `Poll` is invalid.
	 */
	public function Validate(): void{
		$error = new Exceptions\PollInvalidException();

		$this->Name = trim($this->Name ?? '');
		if($this->Name == ''){
			$error->Add(new Exceptions\PollNameRequiredException());
		}
		elseif(strlen($this->Name) > 255){
			$error->Add(new Exceptions\StringTooLongException('Poll name'));
		}

		$this->UrlName = Formatter::MakeUrlSafe($this->Name);

		$this->Description = trim($this->Description ?? '');
		if($this->Description == ''){
			$this->Description = null;
		}

		if(!isset($this->Start, $this->End)){
			$error->Add(new Exceptions\PollDateRequiredException());
		}
		elseif($this->End <= $this->Start){
			$error->Add(new Exceptions\PollDateInvalidException());
		}

		$this->PollItems ??= [];
		$pollItems = [];

		foreach($this->PollItems as $pollItem){
			try{
				$pollItem->Validate();
			}
			catch(Exceptions\ValidationException $ex){
				$error->Add($ex);
			}

			$pollItems[] = $pollItem;
		}

		usort($pollItems, function(PollItem $a, PollItem $b){ return $a->SortOrder <=> $b->SortOrder; });

		foreach($pollItems as $sortOrder => $pollItem){
			$pollItem->SortOrder = $sortOrder + 1;
		}

		if(sizeof($pollItems) < 2){
			$error->Add(new Exceptions\PollItemsRequiredException());
		}

		$this->PollItems = $pollItems;

		if($error->HasExceptions){
			throw $error;
		}
	}

	/**
	 * Create this `Poll`.
	 *
	 * @throws Exceptions\PollInvalidException If the `Poll` contains invalid data.
	 * @throws Exceptions\PollExistsException If a `Poll` with the same `UrlName` exists.
	 */
	public function Create(): void{
		$this->Validate();
		$this->Created = NOW;

		try{
			$this->PollId = Db::QueryInt('
				INSERT into Polls (Created, Name, UrlName, Description, Start, End)
				values (?, ?, ?, ?, ?, ?)
				returning PollId
			', [$this->Created, $this->Name, $this->UrlName, $this->Description, $this->Start, $this->End]);
		}
		catch(Exceptions\DuplicateDatabaseKeyException){
			throw new Exceptions\PollExistsException();
		}

		$this->AddPollItems();
	}

	/**
	 * Save this `Poll`.
	 *
	 * @throws Exceptions\PollInvalidException If the `Poll` is invalid.
	 * @throws Exceptions\PollExistsException If a `Poll` with the same `UrlName` exists.
	 */
	public function Save(): void{
		$this->Validate();

		try{
			Db::Query('
				UPDATE Polls
				set
				Name = ?,
				UrlName = ?,
				Description = ?,
				Start = ?,
				End = ?
				where
				PollId = ?
			', [$this->Name, $this->UrlName, $this->Description, $this->Start, $this->End, $this->PollId]);
		}
		catch(Exceptions\DuplicateDatabaseKeyException){
			throw new Exceptions\PollExistsException();
		}

		// Remove any `PollItems` that were deleted during this edit operation.
		$pollItemIds = [];

		foreach($this->PollItems as $pollItem){
			if(isset($pollItem->PollItemId)){
				$pollItemIds[] = $pollItem->PollItemId;
			}
		}

		$parameters = [$this->PollId];
		$pollItemIdSql = '';

		if(sizeof($pollItemIds) > 0){
			$pollItemIdSql = ' and PollItemId not in ' . Db::CreateSetSql($pollItemIds);
			$parameters = array_merge($parameters, $pollItemIds);
		}

		Db::Query('
			DELETE from PollVotes
			where PollItemId in (
				SELECT PollItemId
				from PollItems
				where PollId = ?' . $pollItemIdSql . '
			)
		', $parameters);

		Db::Query('
			DELETE from PollItems
			where PollId = ?' . $pollItemIdSql,
		$parameters);


		$this->AddPollItems();

		unset($this->_Url);
		unset($this->_EditUrl);
	}

	/**
	 * Fill this `Poll` with values from the current HTTP request body.
	 */
	public function FillFromRequestBody(): void{
		$this->PropertyFromRequest('Name');

		$description = Http::$Request->Body->Get('poll-description', 'empty-string');
		if($description !== null){
			if($description == ''){
				$this->_Description = null;
			}
			else{
				$this->_Description = new Markdown($description);
			}
		}

		$start = Http::$Request->Body->Get('poll-start');
		if($start !== null){
			try{
				$this->Start = (new DateTimeImmutable($start, SITE_TZ))->setTimezone(new DateTimeZone('UTC'));
			}
			catch(\Exception){
				// Pass.
			}
		}

		$end = Http::$Request->Body->Get('poll-end');
		if($end !== null){
			try{
				$this->End = (new DateTimeImmutable($end, SITE_TZ))->setTimezone(new DateTimeZone('UTC'));
			}
			catch(\Exception){
				// Pass.
			}
		}

		$pollItems = [];

		foreach(Http::$Request->Body->Variables as $key => $value){
			preg_match('/^poll-item-name-([0-9]+)$/iu', $key, $matches);
			/** @var string $value */
			if(!isset($matches[1]) || trim($value) == ''){
				continue;
			}

			$pollItemIndex = intval($matches[1]);
			$pollItem = new PollItem();
			$pollItemId = Http::$Request->Body->Get('poll-item-id-' . $pollItemIndex, 'int');

			if($pollItemId !== null){
				$pollItem->PollItemId = $pollItemId;
				$pollItem->PollId = $this->PollId;
			}

			$pollItem->Name = $value;
			$pollItem->Description = Http::$Request->Body->Get('poll-item-description-' . $pollItemIndex);
			$pollItem->SortOrder = Http::$Request->Body->Get('poll-item-sort-order-' . $pollItemIndex, 'int') ?? sizeof($pollItems) + 1;

			$pollItems[] = $pollItem;
		}

		$this->PollItems = $pollItems;
	}

	/**
	 * Create or update the `PollItem`s associated with this `Poll`.
	 */
	private function AddPollItems(): void{
		foreach($this->PollItems as $pollItem){
			$pollItem->PollId = $this->PollId;

			if(isset($pollItem->PollItemId)){
				/** @throws void */
				$pollItem->Save(false);
			}
			else{
				/** @throws void */
				$pollItem->Create(false);
			}
		}
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

	/**
	 * Get all past polls for a specific page, sorted by descending start date.
	 *
	 * @return array{'polls': array<int, Poll>, 'count': int}
	 */
	public static function GetAllPastByPage(int $page = 1, int $perPage = 5): array{
		if($page <= 0){
			$page = 1;
		}

		$offset = (($page - 1) * $perPage);

		$polls = Db::Query('
				SELECT SQL_CALC_FOUND_ROWS *
				from Polls
				where utc_timestamp() >= End
				order by Start desc
				limit ?
				offset ?
			', [$perPage, $offset], Poll::class);

		$count = Db::QueryInt('SELECT found_rows()');

		return ['polls' => $polls, 'count' => $count];
	}
}
