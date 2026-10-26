<?
use Aws\Sns\Message;
use Aws\Sns\MessageValidator;
use function Safe\apcu_fetch;
use function Safe\file_get_contents;
use function Safe\json_decode;
use function Safe\preg_match;

use Safe\DateTimeImmutable;

class AwsSnsMessage{
	public string $Type;
	public string $MessageId;
	public string $TopicArn;
	public DateTimeImmutable $Timestamp;
	public stdClass $Message;

	public function __construct(){
		$this->Message = new stdClass();
	}

	/**
	 * Validate the expected topic and confirm an authorized subscription request.
	 *
	 * @throws Exceptions\AwsSnsMessageInvalidException If the expected topic is empty or does not match the signed message's topic.
	 */
	public function Validate(string $topicArn): void{
		if($topicArn === '' || $this->TopicArn !== $topicArn){
			throw new Exceptions\AwsSnsMessageInvalidException('Unexpected SNS topic.');
		}

		// Confirm subscriptions only after authorizing the signed topic.
		if($this->Type == 'SubscriptionConfirmation' && isset($this->Message->SubscribeUrl)){
			try{
				HttpRequest::Execute(Enums\HttpMethod::Get, $this->Message->SubscribeUrl);
				http_response_code(Enums\HttpCode::NoContent->value);
			}
			catch(Exceptions\HttpRequestException){
				http_response_code(Enums\HttpCode::UnprocessableContent->value);
			}

			exit();
		}
	}

	/**
	 * Read an SNS message from the current HTTP request, validate its AWS signature, and return the parsed message.
	 *
	 * @throws Exceptions\AwsSnsMessageInvalidException If the SNS message is malformed or unsigned.
	 */
	public static function FromHttp(): AwsSnsMessage{
		$object = new AwsSnsMessage();
		$data = [];

		try{
			try{
				/**
				 * @throws \InvalidArgumentException If required SNS fields are missing.
				 * @throws \RuntimeException If the body is not a JSON array or object.
				 */
				$message = Message::fromJsonString(file_get_contents('php://input'));
			}
			catch(\InvalidArgumentException | \RuntimeException $ex){
				throw new Exceptions\AwsSnsMessageInvalidException($ex::class . ': ' . $ex->getMessage());
			}

			/** @var array<string, string> $downloadedCertificates */
			$downloadedCertificates = [];

			// `MessageValidator` uses `file_get_contents()` to get the contents of URLs, which is disabled in our server configuration. So we pass a custom function using an `HttpRequest` to fetch the URL instead.
			$validator = new MessageValidator(function(string $url) use (&$downloadedCertificates): string{
				$cacheKey = 'aws-sns-signing-certificate-' . hash('sha256', $url);

				try{
					/** @var string value */
					$value = apcu_fetch($cacheKey);

					return $value;
				}
				catch(\Exception){
					try{
						$response = HttpRequest::Execute(Enums\HttpMethod::Get, $url);
						$downloadedCertificates[$cacheKey] = $response->Body;

						return $response->Body;
					}
					catch(\Exceptions\HttpRequestException){
						return '';
					}
				}
			});
			$validator->validate($message);

			// Cache newly downloaded certificates only after they successfully validate an SNS message.
			foreach($downloadedCertificates as $cacheKey => $certificate){
				apcu_store($cacheKey, $certificate);
			}

			/** @var array<string, mixed> $data */
			$data = $message->toArray();

			$object->Type = self::GetStringValue($data, 'Type');
			$object->MessageId = self::GetStringValue($data, 'MessageId');
			$object->TopicArn = self::GetStringValue($data, 'TopicArn');
			try{
				$object->Timestamp = new DateTimeImmutable(self::GetStringValue($data, 'Timestamp'));
			}
			catch(\Exception){
				throw new Exceptions\AwsSnsMessageInvalidException('Invalid timestamp.');
			}

			if($object->Type == 'SubscriptionConfirmation'){
				$object->Message->SubscribeUrl = self::GetStringValue($data, 'SubscribeURL');
			}
			elseif($object->Type == 'Notification'){
				$messageBody = self::GetStringValue($data, 'Message');

				if(!preg_match('/^\s*[\{\[]/us', $messageBody)){
					$message = new stdClass();
				}
				else{
					$message = json_decode($messageBody);

					if(!($message instanceof stdClass)){
						throw new Exceptions\AwsSnsMessageInvalidException('No message body.');
					}
				}

				$object->Message = $message;
			}

			return $object;
		}
		catch(Aws\Sns\Exception\InvalidSnsMessageException $ex){
			throw new Exceptions\AwsSnsMessageInvalidException($ex::class . ': ' . $ex->getMessage() . "\nData:\n" . vds($data));
		}
	}

	/**
	 * Get a required string value from the raw SNS message data.
	 *
	 * @param array<string, mixed> $data Raw SNS message data.
	 *
	 * @throws Exceptions\AwsSnsMessageInvalidException If the field is missing or is not a string.
	 */
	private static function GetStringValue(array $data, string $key): string{
		if(!isset($data[$key]) || !is_string($data[$key])){
			throw new Exceptions\AwsSnsMessageInvalidException('Missing SNS field: ' . $key);
		}

		return $data[$key];
	}
}
