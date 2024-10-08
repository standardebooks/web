<?

use Safe\DateTimeImmutable;

class EbookSource{
	public ?int $EbookSourceId = null;
	public ?int $EbookId = null;
	public EbookSourceType $Type;
	public string $Url;

	public static function FromTypeAndUrl(EbookSourceType $type, string $url): EbookSource{
		$instance = new EbookSource();
		$instance->Type = $type;
		$instance->Url = $url;
		return $instance;
	}

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
			INSERT into EbookSources (EbookId, Type, Url)
			values (?,
				?,
				?)
		', [$this->EbookId, $this->Type, $this->Url]);

		$this->EbookSourceId = Db::GetLastInsertedId();
	}
}
