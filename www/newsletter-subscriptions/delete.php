<?
use function Safe\session_start;

try{
	// We may use GET if we're called from an unsubscribe link in an email.
	HttpInput::ValidateRequestMethod([Enums\HttpMethod::Get, Enums\HttpMethod::Delete]);

	$requestType = HttpInput::GetRequestType();

	$newsletterSubscription = NewsletterSubscription::GetByUserUuid(HttpInput::Str(GET, 'user-identifier'), HttpInput::Int(GET, 'newsletter-id'));
	$newsletterSubscription->Delete();

	session_start();
	$_SESSION['is-newsletter-subscriptions-deleted'] = true;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('Location: /users/' . $newsletterSubscription->User->Uuid . '/newsletter-subscriptions');
}
catch(Exceptions\NewsletterSubscriptionNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
