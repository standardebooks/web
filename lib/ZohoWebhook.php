<?
use function Safe\json_decode;

/**
 * A Zoho webhook request with validated signature and decoded payload data.
 */
class ZohoWebhook{
	public stdClass $Data;

	/**
	 * Validate the Zoho webhook signature and decode the webhook data.
	 *
	 * @throws Exceptions\CredentialsInvalidException If the signature is invalid.
	 * @throws Exceptions\WebhookException If the webhook payload is invalid.
	 */
	public function __construct(string $secret){
		$zohoHookSignature = Http::$Request->Headers['x-hook-signature'] ?? '';

		if(!hash_equals($zohoHookSignature, base64_encode(hash_hmac('sha256', Http::$Request->Body->RawBody, $secret, true)))){
			throw new Exceptions\CredentialsInvalidException();
		}

		$data = json_decode(Http::$Request->Body->RawBody);

		if(!($data instanceof stdClass)){
			throw new Exceptions\WebhookException('Couldn\'t decode Zoho webhook payload.', Http::$Request->Body->RawBody);
		}

		$this->Data = $data;
	}
}
