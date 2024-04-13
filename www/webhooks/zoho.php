<?
use Safe\DateTimeImmutable;
use function Safe\file_get_contents;
use function Safe\preg_match;
use function Safe\preg_replace;
use function Safe\json_decode;

// This webhook receives POSTs when email from a Fractured Atlas donation is received
// at the SE Zoho email account. This script processes the email, and inserts the donation ID
// into the database for later processing by ~se/web/scripts/process-pending-payments

$log = new Log(ZOHO_WEBHOOK_LOG_FILE_PATH);

try{
	$log->Write('Received Zoho webhook.');

	if(HttpInput::RequestMethod() != HTTP_POST){
		throw new Exceptions\WebhookException('Expected HTTP POST.');
	}

	$post = file_get_contents('php://input');

	// Validate the Zoho secret.
	/** @var string $zohoWebhookSecret */
	$zohoWebhookSecret = get_cfg_var('se.secrets.zoho.webhook_secret');

	if(!hash_equals($_SERVER['HTTP_X_HOOK_SIGNATURE'], base64_encode(hash_hmac('sha256', $post, $zohoWebhookSecret, true)))){
		throw new Exceptions\InvalidCredentialsException();
	}

	$data = json_decode($post);

	if($data->fromAddress == 'support@fracturedatlas.org' && strpos($data->subject, 'NOTICE:') !== false){
		$log->Write('Processing new donation.');

		// Get the donation ID
		preg_match('/Donation ID: ([0-9a-f\-]+)/us', $data->html, $matches);
		if(sizeof($matches) == 2){
			$transactionId = $matches[1];

			Db::Query('
					INSERT into PendingPayments (Created, ChannelId, TransactionId)
					values (utc_timestamp(),
					        ?,
					        ?)
				', [PAYMENT_CHANNEL_FA, $transactionId]);

			$log->Write('Donation ID: ' . $transactionId);
		}
		else{
			throw new Exceptions\WebhookException('Couldn\'t find donation ID.');
		}
	}

	$log->Write('Event processed.');

	// "Success, no content"
	http_response_code(204);
}
catch(Exceptions\InvalidCredentialsException){
	// "Forbidden"
	$log->Write('Couldn\'t validate POST data.');
	http_response_code(403);
}
catch(Exceptions\WebhookException $ex){
	// Uh oh, something went wrong!
	// Log detailed error and debugging information locally.
	$log->Write('Webhook failed! Error: ' . $ex->getMessage());
	$log->Write('Webhook POST data: ' . $ex->PostData);

	// Print less details to the client.
	print($ex->getMessage());

	// "Client error"
	http_response_code(400);
}
