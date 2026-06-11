<?
/**
 * POST		/webhooks/zoho/donations
 *
 * This script receives `POST` requests when email from a Fractured Atlas donation is received at the SE Zoho email account. It processes the email, and inserts the donation ID into the database for later processing by `~se/web/scripts/process-pending-payments`.
 */

use function Safe\get_cfg_var;
use function Safe\preg_match;

try{
	$log = new Log(ZOHO_WEBHOOK_LOG_FILE_PATH);
	$log->Queue('Received Zoho donations webhook.');

	/** @var string $secret */
	$secret = get_cfg_var('se.secrets.zoho.mail.webhooks.donations_secret');
	$webhook = new ZohoWebhook($secret);

	if($webhook->Data->fromAddress == 'support@fracturedatlas.org' && strpos($webhook->Data->subject, 'NOTICE:') !== false){
		$log->Queue('Processing new donation.');

		// Get the donation ID.
		preg_match('/Donation ID: ([0-9a-f\-]+)/us', $webhook->Data->html, $matches);
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
			throw new Exceptions\WebhookException('Couldn\'t find donation ID.', Http::$Request->Body->RawBody);
		}
	}

	$log->WriteQueue();
	// Don't write out to the log if everything was successful.
	http_response_code(Enums\HttpCode::NoContent->value);
}
catch(Exceptions\CredentialsInvalidException){
	$log->Queue('Couldn\'t validate request signature.');
	$log->WriteQueue();

	http_response_code(Enums\HttpCode::Unauthorized->value);
}
catch(Exceptions\WebhookException $ex){
	// Log detailed error and debugging information.
	$log->Queue('Processing failed: ' . $ex->getMessage());
	$log->Queue('Request body: ' . $ex->PostData);
	$log->WriteQueue();

	// Print fewer details to the client.
	print($ex->getMessage());

	http_response_code(Enums\HttpCode::BadRequest->value);
}
