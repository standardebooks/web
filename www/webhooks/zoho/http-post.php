<?
/**
 * POST		/webhooks/zoho
 *
 * This script receives `POST` requests when email from a Fractured Atlas donation is received at the SE Zoho email account. It processes the email, and inserts the donation ID into the database for later processing by `~se/web/scripts/process-pending-payments`.
 */

use function Safe\file_get_contents;
use function Safe\get_cfg_var;
use function Safe\json_decode;
use function Safe\preg_match;

try{
	$log = new Log(ZOHO_WEBHOOK_LOG_FILE_PATH);
	$log->Queue('Received Zoho webhook.');

	$post = file_get_contents('php://input');

	// Validate the Zoho secret.
	/** @var string $zohoWebhookSecret */
	$zohoWebhookSecret = get_cfg_var('se.secrets.zoho.webhook_secret');

	$zohoHookSignature = Http::$Request->Headers['x-hook-signature'] ?? '';
	if(!hash_equals($zohoHookSignature, base64_encode(hash_hmac('sha256', $post, $zohoWebhookSecret, true)))){
		throw new Exceptions\CredentialsInvalidException();
	}

	/** @var stdClass $data */
	$data = json_decode($post);

	if($data->fromAddress == 'support@fracturedatlas.org' && strpos($data->subject, 'NOTICE:') !== false){
		$log->Queue('Processing new donation.');

		// Get the donation ID.
		preg_match('/Donation ID: ([0-9a-f\-]+)/us', $data->html, $matches);
		if(sizeof($matches) == 2){
			$transactionId = $matches[1];

			Db::Query('
					INSERT into PendingPayments (Created, Processor, TransactionId)
					values (utc_timestamp(),
					        ?,
					        ?)
				', [Enums\PaymentProcessorType::FracturedAtlas, $transactionId]);

			$log->Queue('Donation ID: ' . $transactionId);
		}
		else{
			throw new Exceptions\WebhookException('Couldn\'t find donation ID.');
		}
	}

	$log->Queue('Event processed.');

	// Don't write out to the log if everything was successful.

	http_response_code(Enums\HttpCode::NoContent->value);
}
catch(Exceptions\CredentialsInvalidException){
	$log->Queue('Couldn\'t validate POST data.');
	$log->WriteQueue();
	http_response_code(Enums\HttpCode::Forbidden->value);
}
catch(Exceptions\WebhookException $ex){
	// Uh oh, something went wrong!
	// Log detailed error and debugging information.
	$log->Queue('Webhook failed! Error: ' . $ex->getMessage());
	$log->Queue('Webhook POST data: ' . $ex->PostData);
	$log->WriteQueue();

	// Print fewer details to the client.
	print($ex->getMessage());

	http_response_code(Enums\HttpCode::BadRequest->value);
}
