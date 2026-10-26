<?
/**
 * Receives AWS SNS requests for email spam complaints.
 */

use function Safe\get_cfg_var;
use function Safe\preg_match;

try{
	$message = AwsSnsMessage::FromHttp();

	/** @var string $expectedTopicArn */
	$expectedTopicArn = get_cfg_var('se.secrets.aws.ses.complaint_topic_arn');

	$message->Validate($expectedTopicArn);

	if($message->Type != 'Notification' || ($message->Message->eventType ?? '') != 'Complaint'){
		exit();
	}

	// Unsubscribe the email from all newsletters and stop all further email.
	foreach($message->Message->complaint->complainedRecipients as $complainedRecipient){
		$email = null;
		$result = mailparse_rfc822_parse_addresses($complainedRecipient->emailAddress);
		foreach($result as $address){
			$email = $address['address'] ?? null;
		}

		// Don't act on spam complaints from our own domains/subdomains.
		if($email === null || preg_match('/@(?:[a-z]+\.)*?' . preg_quote(SITE_DOMAIN, '/') . '$/ius', $email)){
			continue;
		}

		NewsletterSubscription::DeleteAllByEmail($email);

		// Can we find the user?
		try{
			$user = User::GetByEmail($email);
		}
		catch(Exceptions\UserNotFoundException){
			// Pass.
		}

		$emailBounce = new EmailBounce();
		$emailBounce->Source = Enums\EmailProviderType::Ses;
		$emailBounce->Email = $email;
		$emailBounce->UserId = $user->UserId ?? null;
		$emailBounce->Type = Enums\EmailBounceType::Spam;
		$emailBounce->Create(); // Also stops all email to the `User`.
	}
}
catch(Exceptions\AwsSnsMessageInvalidException $ex){
	http_response_code(Enums\HttpCode::UnprocessableContent->value);
	throw $ex;
}
