<?
use function Safe\curl_exec;
use function Safe\curl_getinfo;
use function Safe\curl_init;
use function Safe\curl_setopt;
use function Safe\json_encode;

class HttpRequest{
	/**
	 * Execute an HTTP request and return the response.
	 *
	 * @param array<string, mixed>|string|stdClass $data
	 * @param array<string, string> $headers
	 * @param bool $followRedirects **`TRUE`** to follow any 3xx responses until conclusion.
	 *
	 * @throws Exceptions\HttpRequestException If the `curl` request failed.
	 */
	public static function Execute(Enums\HttpMethod $method, string $url, array|string|stdClass $data = [], array $headers = [], $followRedirects = true): HttpResponse{
		$headersLowercase = array_change_key_case($headers, CASE_LOWER);

		if(!is_string($data) && stripos($headersLowercase['content-type'] ?? '', 'application/json') !== false){
			$data = json_encode($data);
		}

		if(is_string($data)){
			$httpData = $data;
		}
		else{
			if($data instanceof stdClass){
				// Convert to array.
				$data = get_object_vars($data);
			}

			$httpData = http_build_query($data);
		}

		$httpHeaders = [];
		foreach($headers as $key => $value){
			$httpHeaders[] = $key . ': ' . $value;
		}

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method->value);
		if($followRedirects){
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		}
		curl_setopt($curl, CURLOPT_USERAGENT, 'Standard Ebooks website');

		if($method != Enums\HttpMethod::Get && $httpData != ''){
			curl_setopt($curl, CURLOPT_POSTFIELDS, $httpData);
		}

		if($method == Enums\HttpMethod::Head){
			curl_setopt($curl, CURLOPT_NOBODY, true); // Issue HTTP HEAD.
		}

		// This function is called by Curl for each header received.
		// See <https://stackoverflow.com/a/41135574>.
		$headers = [];
		curl_setopt($curl, CURLOPT_HEADERFUNCTION, function($curl, $header) use (&$headers){
			$len = strlen($header);
			$header = explode(':', $header, 2);
			if(sizeof($header) < 2){
				// Ignore invalid headers.
				return $len;
			}

			// Don't use `mb_*` functions here.
			$key = strtolower(trim($header[0]));
			$value = trim($header[1]);

			// Duplicate headers are allowed, see <https://stackoverflow.com/a/4371395>.
			if(array_key_exists($key, $headers)){
				$headers[$key] .= ',' . $value;
			}
			else{
				$headers[$key] = $value;
			}

			return $len;
		});

		if(sizeof($headers) > 0){
			curl_setopt($curl, CURLOPT_HTTPHEADER, $httpHeaders);
		}
		try{
			$response = curl_exec($curl);

			if(!is_string($response)){
				throw new Exceptions\HttpRequestException();
			}

			/** @var int $httpCode */
			$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

			/** @var string $finalUrl */
			$finalUrl = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);

			return new HttpResponse(Enums\HttpCode::tryFrom($httpCode) ?? Enums\HttpCode::Ok, $response, $headers, $finalUrl);
		}
		catch(\Safe\Exceptions\CurlException $ex){
			throw new Exceptions\HttpRequestException($ex->getMessage());
		}
	}
}
