<?
require_once('Core.php');

use function Safe\file_get_contents;
use function Safe\preg_match;
use function Safe\preg_replace;
use function Safe\json_decode;

$log = new Log(ZOHO_WEBHOOK_LOG_FILE_PATH);

try{
	$log->Write('Received Zoho webhook.');

	if($_SERVER['REQUEST_METHOD'] != 'POST'){
		throw new Exceptions\WebhookException('Expected HTTP POST.');
	}

	$post = file_get_contents('php://input');

	// Validate the Zoho secret.
	if(!hash_equals($_SERVER['HTTP_X_HOOK_SIGNATURE'], base64_encode(hash_hmac('sha256', $post, preg_replace("/[\r\n]/ius", '', file_get_contents(ZOHO_SECRET_FILE_PATH)), true)))){
		throw new Exceptions\InvalidCredentialsException();
	}

	$data = json_decode($post);

	if($data->fromAddress == 'support@fracturedatlas.org' && strpos($data->subject, 'NOTICE:') !== false){
		$log->Write('Processing new donation.');

		// Get the donation ID
		preg_match('/Donation ID: ([0-9a-f\-]+)/us', $data->html, $matches);
		if(sizeof($matches) == 2){
			$transactionId = $matches[1];

			// FA has a bug where some anonymous donations can't be found in their search form,
			// so we aren't able to get them programatically later. Therefore, store anonymous donations right now
			// instead of queueing them for later retrieval.
			if(preg_match('/Donor: Anonymous/u', $data->html) === 1){
				$payment = new Payment();
				$payment->ChannelId = PAYMENT_CHANNEL_FA;
				$payment->TransactionId = $transactionId;
				$payment->IsRecurring = stripos($data->subject, 'recurring') !== false;
				preg_match('/Amount: \$([\d\.]+)/u', $data->html, $matches);
				if(sizeof($matches) == 2){
					$payment->Amount = $matches[1];
					$payment->Fee = $payment->Amount - ($payment->Amount / 1.087);
				}
				$payment->Create();
			}
			else{
				Db::Query('insert into PendingPayments (Timestamp, ChannelId, TransactionId) values (utc_timestamp(), ?, ?);', [PAYMENT_CHANNEL_FA, $transactionId]);
			}

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
catch(Exceptions\InvalidCredentialsException $ex){
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
