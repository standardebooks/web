<?
session_start();

$subscription = new NewsletterSubscription();

try{
	$subscription = NewsletterSubscription::Get(HttpInput::Str(GET, 'uuid') ?? '');

	if(!$subscription->IsConfirmed){
		$subscription->Confirm();
		$_SESSION['subscription-confirmed'] = $subscription->UserId;
	}

	http_response_code(303);
	header('Location: ' . $subscription->Url);
}
catch(Exceptions\InvalidNewsletterSubscriptionException){
	Template::Emit404();
}
