<?
/**
 * @property-read Markdown $Name
 * @property-write Markdown|string $Name
 * @property-read ?Markdown $Description
 * @property-write Markdown|string|null $Description
 * @property-read int $VoteCount
 * @property Poll $Poll
 */
class PollItem{
	use Traits\Accessor;

	public int $PollItemId;
	public int $PollId;
	public int $SortOrder;

	protected Markdown $_Name;
	protected ?Markdown $_Description;
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

	/**
	 * Set the poll item name as Markdown.
	 */
	protected function SetName(string|Markdown $string): void{
		$this->_Name = new Markdown($string);
	}

	/**
	 * Set the poll item description as Markdown.
	 */
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
		elseif(strlen($this->Name) > 255){
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
			INSERT into PollItems (PollId, Name, Description, SortOrder)
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
			UPDATE PollItems
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
					SELECT *
					from PollItems
					where PollItemId = ?
				', [$pollItemId], PollItem::class);

		return $result[0] ?? throw new Exceptions\PollItemNotFoundException();
	}
}
