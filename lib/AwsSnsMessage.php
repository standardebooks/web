<?
use Aws\Sns\Message;
use Aws\Sns\MessageValidator;
use function Safe\file_get_contents;
use function Safe\json_decode;
use function Safe\preg_match;

use Safe\DateTimeImmutable;

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
			$curl = new HttpRequest();
			try{
				$curl->Execute(Enums\HttpMethod::Get, $this->Message->SubscribeUrl);
			}
			catch(Exceptions\HttpRequestException){
				// Pass.
			}
		}
	}

	/**
	 * Read an SNS message from the current HTTP request, validate its AWS signature and topic, and return the parsed message.
	 *
	 * @throws Exceptions\SnsMessageInvalidException If the SNS message is malformed or unsigned.
	 */
	public static function FromHttp(): AwsSnsMessage{
		$object = new AwsSnsMessage();
		$data = [];

		try{
			$message = Message::fromJsonString(file_get_contents('php://input'));
			// `MessageValidator` uses `file_get_contents()` to get the contents of URLs, which is disabled in our server configuration. So we pass a custom function using an `HttpRequest` to fetch the URL instead.
			$validator = new MessageValidator(function($url){
				try{
					$response = HttpRequest::Execute(Enums\HttpMethod::Get, $url);
					return $response->Body;
				}
				catch(\Exceptions\HttpRequestException){
					return '';
				}
			});
			$validator->validate($message);

			/** @var array<string, mixed> $data */
			$data = $message->toArray();

			$object->Type = self::GetStringValue($data, 'Type');
			$object->MessageId = self::GetStringValue($data, 'MessageId');
			try{
				$object->Timestamp = new DateTimeImmutable(self::GetStringValue($data, 'Timestamp'));
			}
			catch(\Exception){
				throw new Exceptions\SnsMessageInvalidException('Invalid timestamp.');
			}

			if($object->Type == 'SubscriptionConfirmation'){
				$object->Message->SubscribeUrl = self::GetStringValue($data, 'SubscribeURL');
				$object->ConfirmSubscription();
			}
			elseif($object->Type == 'Notification'){
				$messageBody = self::GetStringValue($data, 'Message');

				if(!preg_match('/^\s*[\{\[]/us', $messageBody)){
					$message = new stdClass();
				}
				else{
					$message = json_decode($messageBody);

					if(!($message instanceof stdClass)){
						throw new Exceptions\SnsMessageInvalidException('No message body.');
					}
				}

				$object->Message = $message;
			}

			return $object;
		}
		catch(Aws\Sns\Exception\InvalidSnsMessageException $ex){
			throw new Exceptions\SnsMessageInvalidException($ex::class . ': ' . $ex->getMessage() . "\nData:\n" . vds($data));
		}
	}

	/**
	 * Get a required string value from the raw SNS message data.
	 *
	 * @param array<string, mixed> $data Raw SNS message data.
	 *
	 * @throws Exceptions\SnsMessageInvalidException If the field is missing or is not a string.
	 */
	private static function GetStringValue(array $data, string $key): string{
		if(!isset($data[$key]) || !is_string($data[$key])){
			throw new Exceptions\SnsMessageInvalidException('Missing SNS field: ' . $key);
		}

		return $data[$key];
	}
}
