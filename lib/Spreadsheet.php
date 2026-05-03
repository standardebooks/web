<?
use Safe\DateTimeImmutable;

/**
 * @property-read string $Url
 * @property-read string $EditUrl
 * @property-read string $DeleteUrl
 * @property-read ?Markdown $Notes
 * @property-write Markdown|string|null $Notes
 */
class Spreadsheet{
	use Traits\Accessor;
	use Traits\PropertyFromHttp;

	public int $SpreadsheetId;
	public string $Title;
	public string $ExternalUrl;
	public Enums\SpreadsheetCategory $Category;
	public int $SortOrder;
	public DateTimeImmutable $Created;
	public DateTimeImmutable $Updated;

	protected string $_Url;
	protected string $_EditUrl;
	protected string $_DeleteUrl;
	protected ?Markdown $_Notes = null; // Should be converted to property hooks when PHP 8.4 is available; also see `FillFromHttpPost()`.

	// *******
	// SETTERS
	// *******

	protected function SetNotes(string|Markdown|null $string): void{
		if(isset($string)){
			$this->_Notes = new Markdown($string);
		}
		else{
			$this->_Notes = $string;
		}
	}

	// *******
	// GETTERS
	// *******

	/**
	 * Return the local URL that represents this spreadsheet resource.
	 */
	protected function GetUrl(): string{
		return $this->_Url ??= '/spreadsheets/' . $this->SpreadsheetId;
	}

	/**
	 * Return the URL for the form to edit this spreadsheet.
	 */
	protected function GetEditUrl(): string{
		return $this->_EditUrl ??= $this->Url . '/edit';
	}

	/**
	 * Return the URL for the form to delete this spreadsheet.
	 */
	protected function GetDeleteUrl(): string{
		return $this->_DeleteUrl ??= $this->Url . '/delete';
	}


	// *******
	// METHODS
	// *******

	/**
	 * Validate this spreadsheet before it is saved to the database.
	 *
	 * @throws Exceptions\InvalidSpreadsheetException If the spreadsheet fields are invalid.
	 */
	public function Validate(): void{
		$error = new Exceptions\InvalidSpreadsheetException();

		$this->Title = trim($this->Title ?? '');
		if($this->Title == ''){
			$error->Add(new Exceptions\SpreadsheetTitleRequiredException());
		}
		elseif(strlen($this->Title) > 255){
			$error->Add(new Exceptions\StringTooLongException('title'));
		}

		$this->ExternalUrl = trim($this->ExternalUrl ?? '');
		if($this->ExternalUrl == ''){
			$error->Add(new Exceptions\SpreadsheetExternalUrlRequiredException());
		}
		elseif(strlen($this->ExternalUrl) > 255){
			$error->Add(new Exceptions\StringTooLongException('URL'));
		}
		elseif(filter_var($this->ExternalUrl, FILTER_VALIDATE_URL) === false){
			$error->Add(new Exceptions\InvalidSpreadsheetUrlException($this->ExternalUrl));
		}

		if(!isset($this->Category)){
			$error->Add(new Exceptions\SpreadsheetCategoryRequiredException());
		}

		if(!isset($this->SortOrder)){
			$error->Add(new Exceptions\SpreadsheetSortOrderRequiredException());
		}

		$notes = trim($this->Notes ?? '');
		if($notes == ''){
			$this->Notes = null;
		}
		else{
			$this->Notes = $notes;
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	/**
	 * Create this `Spreadsheet` in the database.
	 *
	 * @throws Exceptions\InvalidSpreadsheetException If the `Spreadsheet` is invalid.
	 * @throws Exceptions\SpreadsheetExistsException If another `Spreadsheet` already uses the same external URL.
	 */
	public function Create(): void{
		if(!isset($this->SortOrder) && isset($this->Category)){
			$this->SortOrder = Db::QueryInt('
						SELECT coalesce(max(SortOrder) + 1, 0)
						from Spreadsheets
						where Category = ?
					', [$this->Category]);
		}

		$this->Validate();
		$this->Created = NOW;

		try{
			$this->SpreadsheetId = Db::QueryInt('
				INSERT into Spreadsheets (Title, ExternalUrl, Category, Notes, SortOrder, Created)
				values (?,
				        ?,
				        ?,
				        ?,
				        ?,
				        ?)
				returning SpreadsheetId
			', [$this->Title, $this->ExternalUrl, $this->Category, $this->Notes, $this->SortOrder, $this->Created]);
		}
		catch(Exceptions\DuplicateDatabaseKeyException){
			throw new Exceptions\SpreadsheetExistsException();
		}
	}

	/**
	 * Save this `Spreadsheet` to the database.
	 *
	 * @throws Exceptions\InvalidSpreadsheetException If the `Spreadsheet` is invalid.
	 * @throws Exceptions\SpreadsheetExistsException If another `Spreadsheet` already uses the same external URL.
	 */
	public function Save(): void{
		$this->Validate();

		try{
			Db::Query('
				UPDATE Spreadsheets
				set Title = ?, ExternalUrl = ?, Category = ?, Notes = ?, SortOrder = ?
				where SpreadsheetId = ?
			', [$this->Title, $this->ExternalUrl, $this->Category, $this->Notes, $this->SortOrder, $this->SpreadsheetId]);
		}
		catch(Exceptions\DuplicateDatabaseKeyException){
			throw new Exceptions\SpreadsheetExistsException();
		}
	}

	/**
	 * Delete this spreadsheet from the database.
	 */
	public function Delete(): void{
		Db::Query('
			DELETE
			from Spreadsheets
			where SpreadsheetId = ?
		', [$this->SpreadsheetId]);
	}

	/**
	 * Fill this spreadsheet from HTTP POST data.
	 */
	public function FillFromHttpPost(): void{
		$this->PropertyFromHttp('Title');
		$this->PropertyFromHttp('ExternalUrl');
		if(isset($_POST['spreadsheet-notes'])){
			$this->Notes = HttpInput::Str(POST, 'spreadsheet-notes');
		}

		$this->PropertyFromHttp('SortOrder');
		$this->PropertyFromHttp('Category');
	}


	// ***********
	// ORM METHODS
	// ***********

	/**
	 * Return a spreadsheet by its ID.
	 *
	 * @throws Exceptions\SpreadsheetNotFoundException If the `Spreadsheet` can't be found.
	 */
	public static function Get(?int $spreadsheetId): Spreadsheet{
		if($spreadsheetId === null){
			throw new Exceptions\SpreadsheetNotFoundException();
		}

		return Db::Query('
				SELECT *
				from Spreadsheets
				where SpreadsheetId = ?
			', [$spreadsheetId], Spreadsheet::class)[0] ?? throw new Exceptions\SpreadsheetNotFoundException();
	}

	/**
	 * Return all `Spreadsheet`s grouped by category.
	 *
	 * @return array<string, array<Spreadsheet>>
	 */
	public static function GetAllGroupedByCategory(): array{
		$output = [];

		foreach(Enums\SpreadsheetCategory::cases() as $category){
			$output[$category->value] = [];
		}

		$spreadsheets = Db::Query('
			SELECT *
			from Spreadsheets
			order by Category asc, SortOrder asc, Title asc
		', [], Spreadsheet::class);

		foreach($spreadsheets as $spreadsheet){
			$output[$spreadsheet->Category->value][] = $spreadsheet;
		}

		return $output;
	}
}
