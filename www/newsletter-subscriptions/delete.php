<?
/**
 * GET/POST/DELETE /users/<uuid>/newsletter-subscriptions/<newsletter-id>/delete
 * Delete a `NewsletterSubscription`.
 *
 * May be called via GET from an unsubscribe link in an email, or via POST from an email client `list-unsubscribe` invocation.
 */
use function Safe\session_start;

try{
	HttpInput::ValidateRequestMethod([Enums\HttpMethod::Get, Enums\HttpMethod::Post, Enums\HttpMethod::Delete]);

	$requestType = HttpInput::GetRequestType();

	$newsletterSubscription = NewsletterSubscription::GetByUserUuid(HttpInput::Str(GET, 'user-identifier'), HttpInput::Int(GET, 'newsletter-id'));
	$newsletterName = $newsletterSubscription->Newsletter->Name;
	$newsletterSubscription->Delete();

	session_start();
	$_SESSION['is-newsletter-subscriptions-deleted'] = true;
	$_SESSION['newsletter-name'] = $newsletterName;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('Location: /users/' . $newsletterSubscription->User->Uuid . '/newsletter-subscriptions');
}
catch(Exceptions\NewsletterSubscriptionNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
