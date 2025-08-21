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
	/** The number of non-bot downloads by feed readers. */
	public int $FeedDownloadCount = 0;
	/** The number of non-bot downloads by users on the website. */
	public int $WebDownloadCount = 0;

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

		if($this->FeedDownloadCount < 0 || $this->FeedDownloadCount > ($this->DownloadCount - $this->BotDownloadCount - $this->WebDownloadCount)){
			$error->Add(new Exceptions\InvalidEbookDownloadCountException('Invalid EbookDownloadSummary FeedDownloadCount: ' . $this->FeedDownloadCount));
		}

		if($this->WebDownloadCount < 0 || $this->WebDownloadCount > ($this->DownloadCount - $this->BotDownloadCount - $this->FeedDownloadCount)){
			$error->Add(new Exceptions\InvalidEbookDownloadCountException('Invalid EbookDownloadSummary WebDownloadCount: ' . $this->WebDownloadCount));
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
			INSERT into EbookDownloadSummaries (EbookId, Date, DownloadCount, BotDownloadCount, UniqueDownloadCount, FeedDownloadCount, WebDownloadCount)
			values (?,
				?,
				?,
				?,
				?,
				?,
				?)
			on duplicate key update
				DownloadCount = value(DownloadCount),
				BotDownloadCount = value(BotDownloadCount),
				UniqueDownloadCount = value(UniqueDownloadCount),
				FeedDownloadCount = value(FeedDownloadCount),
				WebDownloadCount = value(WebDownloadCount)
		', [$this->EbookId, $this->Date, $this->DownloadCount, $this->BotDownloadCount, $this->UniqueDownloadCount, $this->FeedDownloadCount, $this->WebDownloadCount]);
	}
}
