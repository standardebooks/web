<?
/**
 * Receives AWS SNS requests for email open events.
 */

$message = AwsSnsMessage::FromHttp();

if(($message->Message->eventType ?? '') != 'Open'){
	exit();
}

if(isset($message->Message->mail->tags->NewsletterMailingId[0])){
	$newsletterMailingId = (int)$message->Message->mail->tags->NewsletterMailingId[0];
}
else{
	$newsletterMailingId = null;
}

$newsletter = null;

if($newsletterMailingId !== null){
	try{
		$newsletter = Newsletter::GetByNewsletterMailingId($newsletterMailingId);
	}
	catch(Exceptions\NewsletterNotFoundException){
		// Pass, we can continue without having found a newsletter.
	}
}

if($newsletter !== null || $newsletterMailingId !== null){
	foreach($message->Message->mail->commonHeaders->to as $recipient){
		$email = null;
		$result = mailparse_rfc822_parse_addresses($recipient);

		foreach($result as $address){
			$email = $address['address'] ?? null;
		}

		if($email === null){
			continue;
		}

		// We don't want to renew the subscription if the person only opened the reminder email. They will renew it by actually clicking the 'confirm' button.
		if($newsletter !== null && !$isReminderEmail){
			Db::Query('update NewsletterSubscriptions ns inner join NewsletterContacts nc using (NewsletterContactId) set ns.LastOpenTimestamp = utc_timestamp(), HasBeenReminded = false where nc.Email = ? and ns.NewsletterId = ?', [$email, $newsletter->NewsletterId]);

			if($newsletter->NewsletterId == NEWSLETTER_WRITING_TOGETHER){
				// Daily reminders are sent from the same message stream, so also update daily reminders here.
				Db::Query('update NewsletterSubscriptions ns inner join NewsletterContacts nc using (NewsletterContactId) set ns.LastOpenTimestamp = utc_timestamp(), HasBeenReminded = false where nc.Email = ? and ns.NewsletterId = ?', [$email, NEWSLETTER_WRITING_TOGETHER_REMINDERS]);
			}
		}

		if($newsletterMailingId !== null){
			Db::Query('update NewsletterMailings set OpenCount = ifnull(OpenCount, 0) + 1 where NewsletterMailingId = ?', [$newsletterMailingId]);
		}
	}
}
