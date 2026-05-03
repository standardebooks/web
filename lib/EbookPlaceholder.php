<?

/**
 * @property-read bool $IsPublicDomain
 * @property-read string $TimeTillIsPublicDomain A string describing how much longer it will be before this work is in the U.S. public domain, like `3 months` or `20 years`.
 * @property-read ?Markdown $Notes
 * @property-write Markdown|string|null $Notes
 */
class EbookPlaceholder{
	use Traits\Accessor;
	use Traits\PropertyFromHttp;

	public int $EbookId;
	public ?int $YearPublished = null;
	public bool $IsWanted = false;
	public bool $IsInProgress = false;
	public ?Enums\EbookPlaceholderDifficulty $Difficulty = null;
	public ?string $TranscriptionUrl = null;

	protected bool $_IsPublicDomain;
	protected string $_TimeTillIsPublicDomain;
	protected ?Markdown $_Notes = null; // Should be converted to property hooks when PHP 8.4 is available; also see `FillFromHttpPost()`.

	protected function SetNotes(string|Markdown|null $string): void{
		if(isset($string)){
			$this->_Notes = new Markdown($string);
		}
		else{
			$this->_Notes = $string;
		}
	}

	protected function GetIsPublicDomain(): bool{
		if(!isset($this->_IsPublicDomain)){
			if($this->IsWanted){
				// If this book is on our wanted list, we can assume it's already PD. Otherwise works like pulp sci fi, etc., that did not renew would be shown as "not PD yet".
				$this->_IsPublicDomain = true;
			}
			else{
				$this->_IsPublicDomain = $this->YearPublished === null ? true : $this->YearPublished <= PD_YEAR;
			}
		}

		return $this->_IsPublicDomain;
	}

	protected function GetTimeTillIsPublicDomain(): string{
		if(!isset($this->_TimeTillIsPublicDomain)){
			if($this->IsPublicDomain || $this->YearPublished === null){
				$this->_TimeTillIsPublicDomain = '';
			}
			else{
				$years = (int)($this->YearPublished) + 96 - (int)(NOW->format('Y'));
				if($years > 1){
					$this->_TimeTillIsPublicDomain = $years . ' years';
				}
				else{
					$months = 13 - (int)(NOW->format('n'));
					$this->_TimeTillIsPublicDomain = $months . ' month';

					if($months != 1){
						$this->_TimeTillIsPublicDomain .= 's';
					}
				}
			}
		}

		return $this->_TimeTillIsPublicDomain;
	}

	public function FillFromHttpPost(): void{
		$this->PropertyFromHttp('YearPublished');
		$this->PropertyFromHttp('IsWanted');
		$this->PropertyFromHttp('IsInProgress');

		// These properties apply only to books on the SE wanted list.
		if($this->IsWanted){
			$this->PropertyFromHttp('Difficulty');
			$this->PropertyFromHttp('TranscriptionUrl');

			if(isset($_POST['ebook-placeholder-notes'])){
				$this->Notes = HttpInput::Str(POST, 'ebook-placeholder-notes');
			}
		}
	}

	/**
	 * @throws Exceptions\InvalidEbookPlaceholderException
	 */
	public function Validate(): void{
		$thisYear = intval(NOW->format('Y'));
		$error = new Exceptions\InvalidEbookPlaceholderException();

		if(isset($this->YearPublished) && ($this->YearPublished <= 0 || $this->YearPublished > $thisYear)){
			$error->Add(new Exceptions\InvalidEbookPlaceholderYearPublishedException());
		}

		$this->TranscriptionUrl = trim($this->TranscriptionUrl ?? '');
		if($this->TranscriptionUrl == ''){
			$this->TranscriptionUrl = null;
		}

		if($this->Notes == ''){
			$this->Notes = null;
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	/**
	 * @throws Exceptions\InvalidEbookPlaceholderException
	 */
	public function Create(): void{
		$this->Validate();
		Db::Query('
			INSERT into EbookPlaceholders (EbookId, YearPublished, Difficulty, TranscriptionUrl,
				IsWanted, IsInProgress, Notes)
			values (?,
				?,
				?,
				?,
				?,
				?,
				?)
		', [$this->EbookId, $this->YearPublished, $this->Difficulty, $this->TranscriptionUrl,
			$this->IsWanted, $this->IsInProgress, $this->Notes]);
	}

	/**
	 * @throws Exceptions\InvalidEbookPlaceholderException
	 */
	public function Save(): void{
		$this->Validate();
		Db::Query('
			UPDATE
				EbookPlaceholders
			set
			YearPublished = ?,
			Difficulty = ?,
			TranscriptionUrl = ?,
			IsWanted = ?,
			IsInProgress = ?,
			Notes = ?
			where EbookId = ?
		', [$this->YearPublished, $this->Difficulty, $this->TranscriptionUrl,
			$this->IsWanted, $this->IsInProgress, $this->Notes, $this->EbookId]);
	}

	public function Delete(): void{
		Db::Query('
			DELETE
			from EbookPlaceholders
			where EbookId = ?
			',
		[$this->EbookId]);
	}
}
