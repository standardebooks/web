<?
use function Safe\curl_exec;
use function Safe\curl_getinfo;
use function Safe\curl_init;
use function Safe\curl_setopt;
use function Safe\preg_match;
use function Safe\json_encode;

class HttpRequest{
	/**
	 * Execute an HTTP request and return the response.
	 *
	 * To set a custom user-agent, pass the `user-agent` header; otherwise, use the `HTTP_REQUEST_USER_AGENT` constant if defined.
	 *
	 * @param Enums\HttpMethod $method
	 * @param string $url
	 * @param array<string, mixed>|string|stdClass $data Objects and arrays are sent as `x-www-form-urlencoded` key/value pairs, unless `$header` contains a `content-type` key with a value containing `json`, in which case they're sent JSON-encoded.
	 * @param array<string, string> $headers
	 * @param bool $followRedirects **`TRUE`** to follow any 3xx responses until conclusion.
	 *
	 * @throws Exceptions\HttpRequestException If the HTTP request failed, e.g. it was unable to return a response code, or the response couldn't be parsed.
	 */
	public static function Execute(Enums\HttpMethod $method, string $url, array|string|stdClass $data = [], array $headers = [], $followRedirects = true): HttpResponse{
		try{
			$headersLowercase = array_change_key_case($headers, CASE_LOWER);

			if(!is_string($data) && preg_match('/\bjson\b/iu', $headersLowercase['content-type'] ?? '')){
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

			$userAgentString = $headersLowercase['user-agent'] ?? null;

			if($userAgentString === null && defined('HTTP_REQUEST_USER_AGENT')){
				$userAgentString = HTTP_REQUEST_USER_AGENT;
			}

			if($userAgentString !== null){
				curl_setopt($curl, CURLOPT_USERAGENT, $userAgentString);
			}

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

			if(sizeof($httpHeaders) > 0){
				curl_setopt($curl, CURLOPT_HTTPHEADER, $httpHeaders);
			}

			$response = curl_exec($curl);

			if(!is_string($response)){
				throw new Exceptions\HttpRequestException('HTTP request failed.');
			}

			/** @var int $httpCode */
			$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

			/** @var string $finalUrl */
			$finalUrl = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);

			return new HttpResponse(Enums\HttpCode::tryFrom($httpCode) ?? Enums\HttpCode::Ok, $response, $headers, $finalUrl);
		}
		catch(\Exception $ex){
			$exception = new Exceptions\HttpRequestException(message: $ex->getMessage(), previous: $ex);
			$exception->Method = $method;
			$exception->Url = $url;
			$exception->Data = $data;
			throw $exception;
		}
	}
}
