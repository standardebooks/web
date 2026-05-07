<?
use function Safe\json_decode;

class HttpResponse{
	public readonly Enums\HttpCode $HttpCode;
	/** @var array<string, string> $Headers */
	public readonly array $Headers;
	/** The final URL of the request, considering any redirects that occurred during processing. */
	public readonly string $FinalUrl;
	public readonly string $Body;
	/** @var array<stdClass>|stdClass $Json The parsed JSON object if this response was JSON. */
	public readonly array|stdClass $Json;

	/**
	 * @param array<string, string> $headers
	 *
	 * @throws Exceptions\HttpRequestException If the response body is JSON but the body couldn't be parsed.
	 */
	public function __construct(Enums\HttpCode $httpCode, string $body, array $headers, string $finalUrl){
		$this->HttpCode = $httpCode;
		$this->Body = $body;
		$this->Headers = $headers;
		$this->FinalUrl = $finalUrl;

		// If it looks like the response returned JSON, parse it now.
		if(stripos($headers['content-type'] ?? '', 'json') !== false){
			$this->Json = $this->GetJson();
		}
		else{
			$this->Json = new stdClass();
		}
	}

	/**
	 * `HttpResponse` attempts to guess if the response is JSON when constructed, and if so, it populates `HttpResponse::$Json`. This function forces the body to be parsed as JSON.
	 *
	 * @return array<stdClass>|stdClass
	 *
	 * @throws Exceptions\HttpRequestException If the response body couldn't be parsed as JSON.
	 */
	public function GetJson(): array|stdClass{
		$body = $this->Body;
		if($body == ''){
			$body = '{}';
		}

		try{
			/** @var array<stdClass>|stdClass $json */
			$json = json_decode($body);
			return $json;
		}
		catch(Safe\Exceptions\JsonException){
			throw new Exceptions\HttpRequestException('Failed to parse JSON for: ' . $this->Body);
		}
	}
}
