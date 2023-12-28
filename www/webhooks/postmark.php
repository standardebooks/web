<?
use function Safe\curl_exec;
use function Safe\curl_init;
use function Safe\curl_setopt;
use function Safe\file_get_contents;
use function Safe\json_decode;
use function Safe\substr;

$log = new Log(POSTMARK_WEBHOOK_LOG_FILE_PATH);

try{
	/** @var string $smtpUsername */
	$smtpUsername = get_cfg_var('se.secrets.postmark.username');

	$log->Write('Received Postmark webhook.');

	if(HttpInput::RequestMethod() != HTTP_POST){
		throw new Exceptions\WebhookException('Expected HTTP POST.');
	}

	$apiKey = get_cfg_var('se.secrets.postmark.api_key');

	// Ensure this webhook actually came from Postmark
	if($apiKey != ($_SERVER['HTTP_X_SE_KEY'] ?? '')){
		throw new Exceptions\InvalidCredentialsException();
	}

	$post = json_decode(file_get_contents('php://input'));

	if(!$post || !property_exists($post, 'RecordType')){
		throw new Exceptions\WebhookException('Couldn\'t understand HTTP request.', $post);
	}

	if($post->RecordType == 'SpamComplaint'){
		// Received when a user marks an email as spam
		$log->Write('Event type: spam complaint.');

		Db::Query('
				DELETE ns.*
				from NewsletterSubscriptions ns
				inner join Users u using(UserId)
				where u.Email = ?
			', [$post->Email]);
	}
	elseif($post->RecordType == 'SubscriptionChange' && $post->SuppressSending){
		// Received when a user clicks Postmark's "Unsubscribe" link in a newsletter email
		$log->Write('Event type: unsubscribe.');

		$email = $post->Recipient;

		// Remove the email from our newsletter list
		Db::Query('
				DELETE ns.*
				from NewsletterSubscriptions ns
				inner join Users u using(UserId)
				where u.Email = ?
		', [$email]);

		// Remove the suppression from Postmark, since we deleted it from our own list we will never email them again anyway
		$handle = curl_init();
		curl_setopt($handle, CURLOPT_URL, 'https://api.postmarkapp.com/message-streams/' . $post->MessageStream . '/suppressions/delete');
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($handle, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Accept: application/json', 'X-Postmark-Server-Token: ' . $smtpUsername]);
		curl_setopt($handle, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($handle, CURLOPT_POSTFIELDS, '{"Suppressions": [{"EmailAddress": "' . $email . '"}]}');
		curl_exec($handle);
	}
	elseif($post->RecordType == 'SubscriptionChange' && $post->SuppressionReason === null){
		$log->Write('Event type: suppression deletion.');
	}
	else{
		$log->Write('Unrecognized event: ' . $post->RecordType);
	}

	$log->Write('Event processed.');

	// "Success, no content"
	http_response_code(204);
}
catch(Exceptions\InvalidCredentialsException){
	// "Forbidden"
	$log->Write('Invalid key: ' . ($_SERVER['HTTP_X_SE_KEY'] ?? ''));
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
