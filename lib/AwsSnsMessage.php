<?
use function Safe\apcu_fetch;
use function Safe\base64_decode;
use function Safe\file_get_contents;
use function Safe\json_decode;
use function Safe\parse_url;
use function Safe\preg_match;
use function Safe\openssl_pkey_get_public;
use function Safe\openssl_verify;
use function Safe\openssl_x509_read;

use Safe\DateTimeImmutable;
use Safe\Exceptions\ApcuException;

class AwsSnsMessage{
	public string $Type;
	public string $MessageId;
	public DateTimeImmutable $Timestamp;
	public stdClass $Message;

	public function __construct(){
		$this->Message = new stdClass();
	}

	public function ConfirmSubscription(): void{
		if($this->Type == 'SubscriptionConfirmation' && isset($this->Message->SubscribeUrl)){
			$curl = new CurlRequest();
			try{
				$curl->Execute(Enums\HttpMethod::Get, $this->Message->SubscribeUrl);
			}
			catch(Exceptions\CurlException){
				// Pass.
			}
		}
	}

	/**
	 * @throws Exceptions\InvalidSnsMessageException If the `InvalidSnsMessageException` is invalid.
	 */
	public static function FromHttp(): AwsSnsMessage{
		$object = new AwsSnsMessage();
		$data = null;

		try{
			$data = json_decode(file_get_contents('php://input'));
			if(!($data instanceof stdClass)){
				$data = new stdClass();
			}

			if(!self::ValidateSignature($data)){
				throw new Exceptions\InvalidSnsMessageException('Couldn\'t validate message signature.');
			}

			$object->Type = $data->Type ?? throw new Exceptions\InvalidSnsMessageException();
			$object->MessageId = $data->MessageId ?? throw new Exceptions\InvalidSnsMessageException();
			$object->Timestamp = new DateTimeImmutable($data->Timestamp ?? throw new Exceptions\InvalidSnsMessageException());

			if($object->Type == 'SubscriptionConfirmation'){
				$object->Message->SubscribeUrl = $data->SubscribeURL;
				$object->ConfirmSubscription();
			}
			elseif($object->Type = 'Notification'){
				if(!preg_match('/^\s*[\{\[]/us', $data->Message)){
					$message = new stdClass();
				}
				else{
					$message = json_decode($data->Message);

					if(!($message instanceof stdClass)){
						throw new Exceptions\InvalidSnsMessageException('No message body.');
					}
				}

				$object->Message = $message;
			}

			return $object;
		}
		catch(Exceptions\InvalidSnsMessageException $ex){
			throw $ex;
		}
		catch(\Exception $ex){
			throw new Exceptions\InvalidSnsMessageException($ex::class . ': ' . $ex->getMessage() . "\nData:\n" . vds($data));
		}
	}

	/**
	 * @throws Exceptions\InvalidSnsMessageException If the URL can't be retrieved.
	 */
	private static function GetSnsCertificate(string $url): string{
		try{
			/** @var ?string $cert */
			$cert = apcu_fetch('aws-sns-cert-' . $url);
		}
		catch(ApcuException){
			$cert = null;
		}

		if($cert === null){
			try{
				$curl = new CurlRequest();
				$response = $curl->Execute(Enums\HttpMethod::Get, $url);
				$cert = trim($response->Data);
				apcu_store('aws-sns-cert-' . $url, $cert);
			}
			catch(Exception){
				throw new Exceptions\InvalidSnsMessageException();
			}
		}

		return $cert;
	}

	private static function ValidateSignature(stdClass $data): bool{
		$dataArray = (array)$data;

		// Basic required fields.
		foreach(['Type', 'MessageId', 'Signature', 'SigningCertURL', 'SignatureVersion'] as $k){
			if(!array_key_exists($k, $dataArray) || !is_string($dataArray[$k]) || $dataArray[$k] === ''){
				return false;
			}
		}

		// Only SignatureVersion `1` is expected for SNS over HTTP/S.
		if($dataArray['SignatureVersion'] !== '1'){
			return false;
		}

		// Hardening: validate the certificate URL per AWS guidance (https, 443, *.amazonaws.com, expected path).
		$certUrl = $dataArray['SigningCertURL'];
		if(!self::IsValidSnsCertUrl($certUrl)){
			return false;
		}

		$stringToSign = self::BuildSnsStringToSign($data);
		if($stringToSign === null){
			return false; // Unknown `Type` or missing fields for that `Type`.
		}

		try{
			$pem = self::GetSnsCertificate($certUrl);
		}
		catch(Exceptions\InvalidSnsMessageException){
			return false;
		}

		if(trim($pem) === ''){
			return false;
		}

		try{
			$cert = openssl_x509_read($pem);
			$pubKey = openssl_pkey_get_public($cert);
			$sig = base64_decode($dataArray['Signature'], true);
		}
		catch(Exception){
			return false;
		}

		// SNS v1 historically uses SHA1 with RSA, but we try sha256 as a fallback for robustness.
		$ok = (openssl_verify($stringToSign, $sig, $pubKey, OPENSSL_ALGO_SHA1) === 1) || (openssl_verify($stringToSign, $sig, $pubKey, OPENSSL_ALGO_SHA256) === 1);

		return $ok;
	}

	/**
	 * Build the canonical string to sign per SNS docs.
	 *
	 * @return ?string `null` if the message type is unknown or required fields are missing.
	 */
	private static function BuildSnsStringToSign(stdClass $message): ?string{
		$type = $message->Type ?? '';
		$lines = [];

		// Helper to append "Key\nValue\n" only when value is set and non-empty.
		$add = static function(string $key) use ($message, &$lines): bool{
			if(!isset($message->$key) || !is_string($message->$key)){
				return false;
			}
			$lines[] = $key;
			$lines[] = $message->$key;
			return true;
		};

		switch($type){
			case 'Notification':
				// Order matters exactly.
				if(!$add('Message')){
					return null;
				}
				if(!$add('MessageId')){
					return null;
				}
				if(isset($message->Subject) && is_string($message->Subject) && $message->Subject !== ''){
					$add('Subject');
				}
				if(!$add('Timestamp')){
					return null;
				}
				if(!$add('TopicArn')){
					return null;
				}
				if(!$add('Type')){
					return null;
				}
				break;

			case 'SubscriptionConfirmation':
			case 'UnsubscribeConfirmation':
				if(!$add('Message')){
					return null;
				}
				if(!$add('MessageId')){
					return null;
				}
				if(!$add('SubscribeURL')){
					return null;
				}
				if(!$add('Timestamp')){
					return null;
				}
				if(!$add('Token')){
					return null;
				}
				if(!$add('TopicArn')){
					return null;
				}
				if(!$add('Type')){
					return null;
				}
				break;

			default:
				return null;
		}

		// Join with `\n` and add a trailing newline between each pair implicitly:
		// We interleaved keys and values in `$lines`, so we now join with `\n` and ensure a final newline at the end (as SNS expects).
		$str = implode("\n", $lines) . "\n";
		return $str;
	}

	private static function IsValidSnsCertUrl(string $url): bool{
		try{
			$p = parse_url($url);
		}
		catch(Exception){
			return false;
		}

		// Scheme must be https.
		if(($p['scheme'] ?? '') !== 'https'){
			return false;
		}

		// Port must be 443 (explicit or implicit)
		if(isset($p['port']) && (int)$p['port'] !== 443){
			return false;
		}

		// Host must be a subdomain of `amazonaws.com` (no raw IPs).
		$host = strtolower($p['host'] ?? '');
		if($host === '' || filter_var($host, FILTER_VALIDATE_IP)){
			return false;
		}

		if(!preg_match('/(^|\.)amazonaws\.com$/', $host)){
			return false;
		}

		// Path must look like an SNS signing cert.
		$path = $p['path'] ?? '';
		if($path === '' || stripos($path, '/SimpleNotificationService-') === false || !str_ends_with($path, '.pem')){
			return false;
		}

		return true;
	}
}
