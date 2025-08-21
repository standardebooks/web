<?

use Safe\DateTimeImmutable;

class EbookDownload{
	public int $EbookId;
	public DateTimeImmutable $Created;
	public ?string $IpAddress;
	public ?string $UserAgent;

	private const BOT_KEYWORDS = [
		'bot', 'crawl', 'spider', 'slurp', 'chatgpt', 'search',
		'python', 'java', 'curl', 'wget', 'scrape',
		'headless', 'phantom', 'selenium', 'puppeteer',
		'semrushbot', 'dotbot', 'ahrefsbot', 'seokicks', 'dataforseobot', 'proximic',
		'GPTBot', 'ChatGPT-User', 'OAI-SearchBot', 'ClaudeBot', 'claude-web',
		'PerplexityBot', 'Perplexity-User', 'Google-Extended', 'Applebot-Extended'
	];

	/** @var array<string, int> $RateLimitedIpHash */
	protected static array $RateLimitedIpHash;

	/**
	 * Returns a hash of IP addresses of the form:
	 *
	 *     '::ffff:1.2.3.4' => 0,
	 *     '::ffff:5.6.7.8' => 1,
	 *     '::ffff:4.3.2.1' => 2,
	 *
	 * for efficient lookup.
	 *
	 * @return array<string, int>
	 */
	private function GetRateLimitedIpHash(): array{
		if(!isset(EbookDownload::$RateLimitedIpHash)){
			$rateLimitedIps = [];

			$result = RateLimitedIp::GetAll();

			foreach($result as $row){
				$rateLimitedIps[] = $row->IpAddress;
			}

			EbookDownload::$RateLimitedIpHash = array_flip($rateLimitedIps);
		}

		return EbookDownload::$RateLimitedIpHash;
	}

	private function IsRateLimitedIp(): bool{
		if(!isset(EbookDownload::$RateLimitedIpHash)){
			EbookDownload::GetRateLimitedIpHash();
		}

		if(isset($this->IpAddress) && isset(EbookDownload::$RateLimitedIpHash[$this->IpAddress])){
			return true;
		}

		return false;
	}

	public function IsBot(): bool{
		if(empty($this->UserAgent) || strlen($this->UserAgent) < 13){
			return true;
		}

		foreach(EbookDownload::BOT_KEYWORDS as $keyword){
			if(stripos($this->UserAgent, $keyword) !== false){
				return true;
			}
		}

		if($this->IsRateLimitedIp()){
			return true;
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

		$this->IpAddress = Formatter::ToIpv6($this->IpAddress);

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

	/**
	 * Gets the count of `EbookDownload` objects by the given IP address newer than the given created time.
	 *
	 * @param string $ipAddress The IP address to search for.
	 * @param DateTimeImmutable $startDateTime The minimum creation timestamp (inclusive).
	 * @return int The total number of `EbookDownload` objects matching the criteria.
	 */
	public static function GetCountByIpAddressSince(?string $ipAddress, DateTimeImmutable $startDateTime): int{
		if(!isset($ipAddress)){
			return 0;
		}

		$ipAddress = Formatter::ToIpv6($ipAddress);

		return Db::QueryInt('
				SELECT count(*)
				from EbookDownloads
				where IpAddress = ?
					and Created >= ?
			', [$ipAddress, $startDateTime]);
	}
}
