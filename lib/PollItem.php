<?
class PollItem{
	public int $PollItemId;
	public int $PollId;
	public int $SortOrder;

	public Markdown $Name{
		set(string|Markdown $value){
			$this->Name = new Markdown($value);
		}
	}

	public ?Markdown $Description{
		set(string|Markdown|null $value){
			$this->Description = $value === null ? null : new Markdown($value);
		}
	}

	public private(set) int $VoteCount{
		get{
			return $this->VoteCount ??= Db::QueryInt('
							select count(*)
							from PollVotes pv
							inner join PollItems pi using (PollItemId)
							where pi.PollItemId = ?
						', [$this->PollItemId]);
		}
	}

	public Poll $Poll{
		/** @throws Exceptions\PollNotFoundException If the poll no longer exists. */
		get{
			return $this->Poll ??= Poll::Get($this->PollId);
		}
	}


	// *******
	// METHODS
	// *******

	/**
	 * Validate and normalize this poll item before it is created or saved.
	 *
	 * @throws Exceptions\PollItemInvalidException If the poll item contains invalid data.
	 */
	public function Validate(): void{
		$error = new Exceptions\PollItemInvalidException();

		$this->Name = trim($this->Name);
		$this->Description = trim($this->Description ?? '');

		if($this->Description == ''){
			$this->Description = null;
		}

		if($this->Name == ''){
			$error->Add(new Exceptions\PollItemNameRequiredException());
		}
		elseif(mb_strlen($this->Name, 'utf-8') > 255){
			$error->Add(new Exceptions\StringTooLongException('Poll option name'));
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	/**
	 * Create this `PollItem` in the database.
	 *
	 * @throws Exceptions\PollItemInvalidException If the `PollItem` is invalid.
	 */
	public function Create(bool $validate = true): void{
		if($validate){
			$this->Validate();
		}

		$this->PollItemId = Db::QueryInt('
			insert into PollItems (PollId, Name, Description, SortOrder)
			values (?, ?, ?, ?)
			returning PollItemId
		', [$this->PollId, $this->Name, $this->Description, $this->SortOrder]);
	}

	/**
	 * Save this `PollItem`.
	 *
	 * @throws Exceptions\PollItemInvalidException If the `PollItem` is invalid.
	 */
	public function Save(bool $validate = true): void{
		if($validate){
			$this->Validate();
		}

		Db::Query('
			update PollItems
			set
			Name = ?,
			Description = ?,
			SortOrder = ?
			where
			PollItemId = ?
			and PollId = ?
		', [$this->Name, $this->Description, $this->SortOrder, $this->PollItemId, $this->PollId]);
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
					select *
					from PollItems
					where PollItemId = ?
				', [$pollItemId], PollItem::class);

		return $result[0] ?? throw new Exceptions\PollItemNotFoundException();
	}
}
