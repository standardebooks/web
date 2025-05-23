<?

use Safe\DateTimeImmutable;

class EbookDownloadSummary{
	public int $EbookId;
	public DateTimeImmutable $Date;
	/** The number of downloads by non-bot clients on the given date. */
	public int $DownloadCount = 0;
	/** The number of downloads by bot clients on the given date. */
	public int $BotDownloadCount = 0;

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
			INSERT into EbookDownloadSummaries (EbookId, Date, DownloadCount, BotDownloadCount)
			values (?,
				?,
				?,
				?)
			on duplicate key update
				DownloadCount = value(DownloadCount),
				BotDownloadCount = value(BotDownloadCount)
		', [$this->EbookId, $this->Date, $this->DownloadCount, $this->BotDownloadCount]);
	}
}
