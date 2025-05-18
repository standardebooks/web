<?

use Safe\DateTimeImmutable;

class EbookDownload{
	public int $EbookId;
	public DateTimeImmutable $Created;
	public ?string $IpAddress;
	public ?string $UserAgent;

	public function IsBot(): bool{
		if(empty($this->UserAgent) || strlen($this->UserAgent) < 20){
			return true;
		}

		$botKeywords = [
			'bot', 'crawl', 'spider', 'slurp', 'chatgpt', 'search',
			'python', 'java', 'curl', 'wget', 'scrape'
		];

		foreach($botKeywords as $keyword){
			if(strpos($this->UserAgent, $keyword) !== false){
				return true;
			}
		}

		return false;
	}

	/**
	 * @throws Exceptions\InvalidEbookDownloadException
	 */
	public function Validate(): void{
		$error = new Exceptions\InvalidEbookDownloadException();

		if(!isset($this->EbookId)){
			$error->Add(new Exceptions\EbookDownloadEbookIdRequiredException());
		}

		if($this->IpAddress == ''){
			$this->IpAddress = null;
		}

		if($this->UserAgent == ''){
			$this->UserAgent = null;
		}

		// The `IpAddress` column expects IPv6 address strings.
		if(is_string($this->IpAddress) && filter_var($this->IpAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)){
			$this->IpAddress = '::ffff:' . $this->IpAddress;
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	/**
	 * @throws Exceptions\InvalidEbookDownloadException
	 */
	public function Create(): void{
		$this->Validate();

		$this->Created = NOW;

		Db::Query('
			INSERT into EbookDownloads (EbookId, Created, IpAddress, UserAgent)
			values (?,
				?,
				?,
				?)
		', [$this->EbookId, $this->Created, $this->IpAddress, $this->UserAgent]);
	}

	/**
	 * @return array<EbookDownload>
	 */
	public static function GetAllByDate(DateTimeImmutable $date): array{
		$startDate = $date->setTime(0, 0, 0);
		$endDate = $date->setTime(0, 0, 0)->modify('+1 day');

		return Db::Query('
				SELECT *
				from EbookDownloads
				where Created >= ?
					and Created < ?
			', [$startDate, $endDate], EbookDownload::class);
	}
}
