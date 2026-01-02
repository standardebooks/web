<?
use function Safe\curl_exec;
use function Safe\curl_getinfo;
use function Safe\curl_init;
use function Safe\curl_setopt;
use function Safe\json_decode;
use function Safe\json_encode;

class CurlRequest{
	/**
	 * @param array<string, mixed>|string|stdClass $data
	 * @param array<string, string> $headers
	 *
	 * @throws Exceptions\CurlException If the `curl` request failed.
	 */
	public static function Execute(Enums\HttpMethod $method, string $url, array|string|stdClass $data = [], array $headers = []): CurlStringResponse{
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
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method->value);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

		if($method != Enums\HttpMethod::Get && $httpData != ''){
			curl_setopt($curl, CURLOPT_POSTFIELDS, $httpData);
		}

		if(sizeof($headers) > 0){
			curl_setopt($curl, CURLOPT_HTTPHEADER, $httpHeaders);
		}
		try{
			$response = curl_exec($curl);

			if(!is_string($response)){
				throw new Exceptions\CurlException();
			}

			/** @var int $httpCode */
			$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

			$returnValue = new CurlStringResponse();
			$returnValue->HttpCode = $httpCode;
			$returnValue->Data = $response;

			return $returnValue;
		}
		catch(\Safe\Exceptions\CurlException $ex){
			throw new Exceptions\CurlException($ex->getMessage());
		}
	}

	/**
	 * @param array<string, mixed>|string|stdClass $data
	 * @param array<string, string> $headers
	 *
	 * @throws Exceptions\CurlException If the `curl` request failed.
	 */
	public static function ExecuteJson(Enums\HttpMethod $method, string $url, array|string|stdClass $data = [], array $headers = []): CurlJsonResponse{
		if(!is_string($data) && stripos($headers['Content-Type'] ?? '', 'json') !== false){
			$data = json_encode($data);
		}

		$response = CurlRequest::Execute($method, $url, $data, $headers);

		if($response->Data == ''){
			$response->Data = '{}';
		}

		try{
			/** @var array<stdClass>|stdClass $json */
			$json = json_decode($response->Data);
		}
		catch(Safe\Exceptions\JsonException){
			$exception = new Exceptions\CurlException('Curl failed to parse JSON response. Response: ' . $response->Data);
			throw $exception;
		}

		$jsonResponse = new CurlJsonResponse();
		$jsonResponse->HttpCode = $response->HttpCode;
		$jsonResponse->Data = $json;

		return $jsonResponse;
	}
}
