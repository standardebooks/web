<?

/**
 * @property bool $IsPublicDomain
 */
class EbookPlaceholder{
	use Traits\Accessor;
	use Traits\PropertyFromHttp;

	public int $EbookId;
	public ?int $YearPublished = null;
	public bool $IsWanted = false;
	public ?Enums\EbookPlaceholderStatus $Status = null;
	public ?Enums\EbookPlaceholderDifficulty $Difficulty = null;
	public ?string $TranscriptionUrl = null;
	public bool $IsPatron = false;
	public ?string $Notes = null;

	protected bool $_IsPublicDomain;

	protected function GetIsPublicDomain(): bool{
		if(!isset($this->_IsPublicDomain)){
			$this->_IsPublicDomain = $this->YearPublished === null ? true : $this->YearPublished <= PD_YEAR;
		}

		return $this->_IsPublicDomain;
	}

	public function FillFromHttpPost(): void{
		$this->PropertyFromHttp('YearPublished');
		$this->PropertyFromHttp('IsWanted');

		// These properties apply only to books on the SE wanted list.
		if($this->IsWanted){
			$this->PropertyFromHttp('Status');
			$this->PropertyFromHttp('Difficulty');
			$this->PropertyFromHttp('TranscriptionUrl');
			$this->PropertyFromHttp('IsPatron');
			$this->PropertyFromHttp('Notes');
		}
	}

	/**
	 * @throws Exceptions\ValidationException
	 */
	public function Validate(): void{
		$thisYear = intval(NOW->format('Y'));
		$error = new Exceptions\ValidationException();

		if(isset($this->YearPublished) && ($this->YearPublished <= 0 || $this->YearPublished > $thisYear)){
			$error->Add(new Exceptions\InvalidEbookPlaceholderYearPublishedException());
		}

		$this->TranscriptionUrl = trim($this->TranscriptionUrl ?? '');
		if($this->TranscriptionUrl == ''){
			$this->TranscriptionUrl = null;
		}

		$this->Notes = trim($this->Notes ?? '');
		if($this->Notes == ''){
			$this->Notes = null;
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	/**
	 * @throws Exceptions\ValidationException
	 */
	public function Create(): void{
		$this->Validate();
		Db::Query('
			INSERT into EbookPlaceholders (EbookId, YearPublished, Status, Difficulty, TranscriptionUrl,
				IsWanted, IsPatron, Notes)
			values (?,
				?,
				?,
				?,
				?,
				?,
				?,
				?)
		', [$this->EbookId, $this->YearPublished, $this->Status, $this->Difficulty, $this->TranscriptionUrl,
			$this->IsWanted, $this->IsPatron, $this->Notes]);
	}
}
