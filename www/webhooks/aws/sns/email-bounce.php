<?
/**
 * Receives AWS SNS requests for email bounces, out-of-office replies, and addresses that are on the AWS global suppresions list.
 */

$message = AwsSnsMessage::FromHttp();

if(($message->Message->eventType ?? '') != 'Bounce'){
	exit();
}

// Out-of-office replies have `BounceType` = `Transient` and `BounceSubType` = `General`, so ignore those.

if(
	$message->Message->bounce->bounceType == 'Permanent'
	||
	$message->Message->bounce->bounceType == 'Undetermined'
	||
	(
		$message->Message->bounce->bounceType == 'Transient'
		&&
		(
			$message->Message->bounce->bounceSubType == 'MailboxFull'
			||
			$message->Message->bounce->bounceSubType == 'ContentRejected'
			||
			(
				$message->Message->bounce->bounceSubType == 'General'
				&&
				(
					// SMTP errors starting with 5 are always permanent.
					($message->Message->bounce->bouncedRecipients[0]->status ?? ' ')[0] == '5'
					||
					stripos($message->Message->bounce->bouncedRecipients[0]->diagnosticCode ?? '', 'expired') !== false
				)
			)
		)
	)
){
	// Unsubscribe the email from all newsletters and stop further email.
	foreach($message->Message->bounce->bouncedRecipients as $bouncedRecipient){
		$email = $bouncedRecipient->emailAddress;

		try{
			$newsletterContact = NewsletterContact::GetByEmail($email);
			$newsletterContact->Delete(); // Also deletes all related `NewsletterSubscriptions`.
		}
		catch(Exceptions\NewsletterContactNotFoundException){
			// Couldn't find the email, pass.
		}

		// Can we find the user?
		try{
			$user = User::GetByEmail($email);
		}
		catch(Exceptions\UserNotFoundException){
			// Couldn't find the `User`, pass.
		}

		$emailBounce = new EmailBounce();
		$emailBounce->Source = Enums\EmailProviderType::Ses;
		$emailBounce->Email = $email;
		$emailBounce->UserId = $user->UserId ?? null;
		if($message->Message->bounce->bounceType == 'Transient'){
			$emailBounce->Type = Enums\EmailBounceType::Soft;
		}
		else{
			$emailBounce->Type = Enums\EmailBounceType::Hard;
		}

		$emailBounce->Create(); // Also stops all email to the `User`.
	}
}
