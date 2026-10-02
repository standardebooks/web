<?

class EbookPlaceholder{
	use Traits\PropertyFromRequest;

	public int $EbookId;
	public ?int $YearPublished = null;
	public bool $IsWanted = false;
	public bool $IsInProgress = false;
	public ?Enums\EbookPlaceholderDifficulty $Difficulty = null;
	public ?string $TranscriptionUrl = null;

	public private(set) bool $IsPublicDomain{
		get{
			if(!isset($this->IsPublicDomain)){
				if($this->IsWanted){
					// If this book is on our wanted list, we can assume it's already PD. Otherwise works like pulp sci fi, etc., that did not renew would be shown as "not PD yet".
					$this->IsPublicDomain = true;
				}
				else{
					$this->IsPublicDomain = $this->YearPublished === null ? true : $this->YearPublished <= PD_YEAR;
				}
			}

			return $this->IsPublicDomain;
		}
	}

	public private(set) string $TimeTillIsPublicDomain{
		get{
			if(!isset($this->TimeTillIsPublicDomain)){
				if($this->IsPublicDomain || $this->YearPublished === null){
					$this->TimeTillIsPublicDomain = '';
				}
				else{
					if($this->YearPublished >= 1978){
						// Date of author's death + 70 years.
						$this->TimeTillIsPublicDomain = '70 years after the author’s death';
					}
					else{
						// Publication year + 96 years.
						$years = (int)($this->YearPublished) + 96 - (int)(NOW->format('Y'));
						if($years > 1){
							$this->TimeTillIsPublicDomain = $years . ' years';
						}
						else{
							$months = 13 - (int)(NOW->format('n'));
							$this->TimeTillIsPublicDomain = $months . ' ' . Formatter::Pluralize($months, 'month');
						}
					}
				}
			}

			return $this->TimeTillIsPublicDomain;
		}
	}

	public ?Markdown $Notes = null{
		set(string|Markdown|null $value){
			$this->Notes = $value === null ? null : new Markdown($value);
		}
	}

	public function FillFromRequestBody(): void{
		$this->PropertyFromRequest('YearPublished');
		$this->PropertyFromRequest('IsWanted');
		$this->PropertyFromRequest('IsInProgress');

		// These properties apply only to books on the SE wanted list.
		if($this->IsWanted){
			$this->PropertyFromRequest('Difficulty');
			$this->PropertyFromRequest('TranscriptionUrl');

			if(isset(Http::$Request->Body->Variables['ebook-placeholder-notes'])){
				$this->Notes = Http::$Request->Body->Get('ebook-placeholder-notes');
			}
		}
	}

	/**
	 * @throws Exceptions\EbookPlaceholderInvalidException
	 */
	public function Validate(): void{
		$thisYear = intval(NOW->format('Y'));
		$error = new Exceptions\EbookPlaceholderInvalidException();

		if(isset($this->YearPublished) && ($this->YearPublished <= 0 || $this->YearPublished > $thisYear)){
			$error->Add(new Exceptions\EbookPlaceholderYearPublishedInvalidException());
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
	 * @throws Exceptions\EbookPlaceholderInvalidException
	 */
	public function Create(): void{
		$this->Validate();
		Db::Query('
			insert into EbookPlaceholders (EbookId, YearPublished, Difficulty, TranscriptionUrl,
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
	 * @throws Exceptions\EbookPlaceholderInvalidException
	 */
	public function Save(): void{
		$this->Validate();
		Db::Query('
			update
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
			delete
			from EbookPlaceholders
			where EbookId = ?
			',
		[$this->EbookId]);
	}
}
