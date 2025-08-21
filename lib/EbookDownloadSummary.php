<?

use Safe\DateTimeImmutable;

class EbookDownloadSummary{
	public int $EbookId;
	public DateTimeImmutable $Date;
	/** The total number of downloads, including bot downloads, on the given date. */
	public int $DownloadCount = 0;
	/** The number of downloads by bot clients on the given date. */
	public int $BotDownloadCount = 0;
	/** The number of unique non-bot downloads. If the same non-bot IP downloads the same book 3 times in a day, this counts only 1 unique download. */
	public int $UniqueDownloadCount = 0;

	public function __construct(int $ebookId, DateTimeImmutable $date){
		$this->EbookId = $ebookId;
		$this->Date = $date;
	}

	/**
	 * @throws Exceptions\InvalidEbookDownloadSummaryException
	 */
	public function Validate(): void{
		$error = new Exceptions\InvalidEbookDownloadSummaryException();

		if($this->DownloadCount < 0){
			$error->Add(new Exceptions\InvalidEbookDownloadCountException('Invalid EbookDownloadSummary DownloadCount: ' . $this->DownloadCount));
		}

		if($this->BotDownloadCount < 0){
			$error->Add(new Exceptions\InvalidEbookDownloadCountException('Invalid EbookDownloadSummary BotDownloadCount: ' . $this->BotDownloadCount));
		}

		if($this->UniqueDownloadCount < 0 || $this->UniqueDownloadCount > ($this->DownloadCount - $this->BotDownloadCount)){
			$error->Add(new Exceptions\InvalidEbookDownloadCountException('Invalid EbookDownloadSummary UniqueDownloadCount: ' . $this->UniqueDownloadCount));
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	/**
	 * @throws Exceptions\InvalidEbookDownloadSummaryException
	 */
	public function Create(): void{
		$this->Validate();

		Db::Query('
			INSERT into EbookDownloadSummaries (EbookId, Date, DownloadCount, BotDownloadCount, UniqueDownloadCount)
			values (?,
				?,
				?,
				?,
				?)
			on duplicate key update
				DownloadCount = value(DownloadCount),
				BotDownloadCount = value(BotDownloadCount),
				UniqueDownloadCount = value(UniqueDownloadCount)
		', [$this->EbookId, $this->Date, $this->DownloadCount, $this->BotDownloadCount, $this->UniqueDownloadCount]);
	}
}
