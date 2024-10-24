<?

use Safe\DateTimeImmutable;

class EbookSource{
	public ?int $EbookId = null;
	public EbookSourceType $Type;
	public string $Url;
	public ?int $SortOrder = null;

	/**
	 * @throws Exceptions\ValidationException
	 */
	public function Validate(): void{
		/** @throws void */
		$now = new DateTimeImmutable();

		$error = new Exceptions\ValidationException();

		if(!isset($this->EbookId)){
			$error->Add(new Exceptions\EbookSourceEbookIdRequiredException());
		}

		if(isset($this->Url)){
			$this->Url = trim($this->Url);

			if($this->Url == ''){
				$error->Add(new Exceptions\EbookSourceUrlRequiredException());
			}
		}
		else{
			$error->Add(new Exceptions\EbookSourceUrlRequiredException());
		}

		if(!isset($this->SortOrder)){
			$error->Add(new Exceptions\EbookSourceSortOrderRequiredException());
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
			INSERT into EbookSources (EbookId, Type, Url, SortOrder)
			values (?,
				?,
				?,
				?)
		', [$this->EbookId, $this->Type, $this->Url, $this->SortOrder]);
	}
}
