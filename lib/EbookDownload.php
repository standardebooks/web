<?

use Safe\DateTimeImmutable;

use function Safe\curl_exec;
use function Safe\curl_init;
use function Safe\curl_setopt;
use function Safe\inet_pton;
use function Safe\json_decode;

class EbookDownload{
	public int $EbookId;
	public DateTimeImmutable $Created;
	public ?string $IpAddress;
	public ?string $UserAgent;

	private const AWS_IP_RANGES_URL = 'https://ip-ranges.amazonaws.com/ip-ranges.json';
	// The Azure URL updates periodically at: https://www.microsoft.com/en-us/download/details.aspx?id=56519
	private const AZURE_IP_RANGES_URL = 'https://download.microsoft.com/download/7/1/d/71d86715-5596-4529-9b13-da13a5de5b63/ServiceTags_Public_20250526.json';
	private const GCP_IP_RANGES_URL = 'https://www.gstatic.com/ipranges/cloud.json';

	private const BOT_KEYWORDS = [
		'bot', 'crawl', 'spider', 'slurp', 'chatgpt', 'search',
		'python', 'java', 'curl', 'wget', 'scrape',
		'headless', 'phantom', 'selenium', 'puppeteer',
		'semrushbot', 'dotbot', 'ahrefsbot', 'seokicks', 'dataforseobot', 'proximic'
	];

	/** @var array<string> $CLOUD_IP_BINARY_RANGES */
	private static array $CLOUD_IP_BINARY_RANGES = [];

	public static function UpdateCloudIpRanges(): void{
		$allRanges = [];

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		// AWS
		try{
			curl_setopt($curl, CURLOPT_URL, EbookDownload::AWS_IP_RANGES_URL);
			$awsData = json_decode((string)curl_exec($curl), true);

			// Process IPv4 ranges.
			if(isset($awsData['prefixes'])){
				foreach($awsData['prefixes'] as $prefix){
					if(isset($prefix['ip_prefix'])){
						$allRanges[] = $prefix['ip_prefix'];
					}
				}
			}

			// Process IPv6 ranges.
			if(isset($awsData['ipv6_prefixes'])){
				foreach($awsData['ipv6_prefixes'] as $prefix){
					if(isset($prefix['ipv6_prefix'])){
						$allRanges[] = $prefix['ipv6_prefix'];
					}
				}
			}
		} catch (Throwable $e) {
			print("Failed to fetch AWS IP ranges: " . $e->getMessage());
		}


		// Azure
		try{
			curl_setopt($curl, CURLOPT_URL, EbookDownload::AZURE_IP_RANGES_URL);
			$azureData = json_decode((string)curl_exec($curl), true);

			if(isset($azureData['values'])){
				foreach($azureData['values'] as $region){
					$properties = $region['properties'] ?? [];

					if(isset($properties['addressPrefixes'])){
						foreach($properties['addressPrefixes'] as $prefix){
							if(isset($prefix)){
								$allRanges[] = $prefix;
							}
						}
					}
				}
			}
		} catch (Throwable $e) {
			print("Failed to fetch Azure IP ranges: " . $e->getMessage());
		}

		// GCP
		try{
			curl_setopt($curl, CURLOPT_URL, EbookDownload::GCP_IP_RANGES_URL);
			$gcpData = json_decode((string)curl_exec($curl), true);

			if(isset($gcpData['prefixes'])){
				foreach($gcpData['prefixes'] as $prefix){
					if(isset($prefix['ipv4Prefix'])){
						$allRanges[] = $prefix['ipv4Prefix'];
					}elseif(isset($prefix['ipv6Prefix'])){
						$allRanges[] = $prefix['ipv6Prefix'];
					}
				}
			}
		} catch (Throwable $e) {
			print("Failed to fetch GCP IP ranges: " . $e->getMessage());
		}

		EbookDownload::SetCloudIpRanges($allRanges);
	}

	/**
	 * @param array<string> $ranges Array of CIDR ranges (e.g., ['34.1.208.0/20', '2600:1900:8000::/44'])
	 */
	private static function SetCloudIpRanges(array $ranges): void{
		EbookDownload::$CLOUD_IP_BINARY_RANGES = [];

		foreach($ranges as $cidr){
			$range = EbookDownload::CidrToBinaryRange($cidr);
			if($range !== null){
				EbookDownload::$CLOUD_IP_BINARY_RANGES[] = $range;
			}
		}
	}

	/**
	 * Convert CIDR notation to binary range (works for both IPv4 and IPv6)
	 * @param string $cidr CIDR notation (e.g., '34.1.208.0/20' or '2600:1900:8000::/44')
	 * @return array{start: string, end: string}|null
	 */
	private static function CidrToBinaryRange(string $cidr): ?array{
		if(!str_contains($cidr, '/')){
			return null;
		}

		[$subnet, $bits] = explode('/', $cidr, 2);
		$bits = (int)$bits;

		$subnetBin = inet_pton($subnet);
		$addressLength = strlen($subnetBin);
		$maxBits = $addressLength * 8;

		// Calculate how many full bytes and remaining bits.
		$fullBytes = intdiv($bits, 8);
		$remainingBits = $bits % 8;

		// Create network address by masking the subnet.
		$networkBin = $subnetBin;

		// Zero out bits beyond the prefix length.
		for($i = $fullBytes; $i < $addressLength; $i++){
			if($i === $fullBytes && $remainingBits > 0){
				// Partial byte - mask the remaining bits.
				$mask = 0xFF << (8 - $remainingBits);
				$networkBin[$i] = chr(ord($networkBin[$i]) & $mask);
			}else{
				// Full byte - zero it out.
				$networkBin[$i] = "\x00";
			}
		}

		// Create broadcast address by setting all host bits to 1.
		$broadcastBin = $networkBin;
		for($i = $fullBytes; $i < $addressLength; $i++){
			if($i === $fullBytes && $remainingBits > 0){
				// Partial byte - set remaining bits to 1.
				$mask = 0xFF >> $remainingBits;
				$broadcastBin[$i] = chr(ord($broadcastBin[$i]) | $mask);
			}else{
				// Full byte - set to 0xFF.
				$broadcastBin[$i] = "\xFF";
			}
		}

		return [
			'start' => $networkBin,
			'end' => $broadcastBin
		];
	}


	private static function IsCloudIp(string $ip): bool{
		// Remove IPv6-mapped IPv4 prefix if present.
		if(str_starts_with($ip, '::ffff:')){
			$ip = substr($ip, 7);
		}

		$ipBin = inet_pton($ip);

		// Check if IP binary falls within any of the pre-computed binary ranges.
		foreach(EbookDownload::$CLOUD_IP_BINARY_RANGES as $range){
			if(EbookDownload::IpBinaryInRange($ipBin, $range['start'], $range['end'])){
				return true;
			}
		}

		return false;
	}

	private static function IpBinaryInRange(string $ipBin, string $startBin, string $endBin): bool{
		// IPs must be same length (IPv4 vs IPv6).
		if(strlen($ipBin) !== strlen($startBin)){
			return false;
		}

		return strcmp($ipBin, $startBin) >= 0 && strcmp($ipBin, $endBin) <= 0;
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

		if($this->IpAddress !== null && EbookDownload::IsCloudIp($this->IpAddress)){
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
