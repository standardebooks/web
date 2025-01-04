<?
use function Safe\curl_exec;
use function Safe\curl_init;
use function Safe\curl_setopt;
use function Safe\file_get_contents;
use function Safe\json_decode;

try{
	$log = new Log(POSTMARK_WEBHOOK_LOG_FILE_PATH);
	/** @var string $smtpUsername */
	$smtpUsername = get_cfg_var('se.secrets.postmark.username');

	$log->Write('Received Postmark webhook.');

	HttpInput::ValidateRequestMethod([Enums\HttpMethod::Post]);

	$apiKey = get_cfg_var('se.secrets.postmark.api_key');

	// Ensure this webhook actually came from Postmark.
	if($apiKey != ($_SERVER['HTTP_X_SE_KEY'] ?? '')){
		throw new Exceptions\InvalidCredentialsException();
	}

	$post = file_get_contents('php://input');

	/** @var stdClass $data */
	$data = json_decode($post);

	if(!property_exists($data, 'RecordType')){
		throw new Exceptions\WebhookException('Couldn\'t understand HTTP request.', $post);
	}

	if($data->RecordType == 'SpamComplaint'){
		// Received when a user marks an email as spam.
		$log->Write('Event type: spam complaint.');

		Db::Query('
				DELETE ns.*
				from NewsletterSubscriptions ns
				inner join Users u using(UserId)
				where u.Email = ?
			', [$data->Email]);
	}
	elseif($data->RecordType == 'SubscriptionChange' && $data->SuppressSending){
		// Received when a user clicks Postmark's "Unsubscribe" link in a newsletter email.
		$log->Write('Event type: unsubscribe.');

		$email = $data->Recipient;

		// Remove the email from our newsletter list.
		Db::Query('
				DELETE ns.*
				from NewsletterSubscriptions ns
				inner join Users u using(UserId)
				where u.Email = ?
		', [$email]);

		// Remove the suppression from Postmark, since we deleted it from our own list we will never email them again anyway.
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'https://api.postmarkapp.com/message-streams/' . $data->MessageStream . '/suppressions/delete');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Accept: application/json', 'X-Postmark-Server-Token: ' . $smtpUsername]);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_POSTFIELDS, '{"Suppressions": [{"EmailAddress": "' . $email . '"}]}');
		curl_exec($curl);
	}
	elseif($data->RecordType == 'SubscriptionChange' && $data->SuppressionReason === null){
		$log->Write('Event type: suppression deletion.');
	}
	else{
		$log->Write('Unrecognized event: ' . $data->RecordType);
	}

	$log->Write('Event processed.');

	http_response_code(Enums\HttpCode::NoContent->value);
}
catch(Exceptions\InvalidCredentialsException){
	$log->Write('Invalid key: ' . ($_SERVER['HTTP_X_SE_KEY'] ?? ''));
	http_response_code(Enums\HttpCode::Forbidden->value);
}
catch(Exceptions\WebhookException $ex){
	// Uh oh, something went wrong!
	// Log detailed error and debugging information locally.
	$log->Write('Webhook failed! Error: ' . $ex->getMessage());
	$log->Write('Webhook POST data: ' . $ex->PostData);

	// Print less details to the client.
	print($ex->getMessage());

	http_response_code(Enums\HttpCode::BadRequest->value);
}
