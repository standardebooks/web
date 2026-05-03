<?
/**
 * DELETE	/users/:uuid/newsletter-subscriptions/:newsletter-id
 *
 * Delete a `NewsletterSubscription`.
 *
 * May be called via `GET` from an unsubscribe link in an email, or via `POST` from an email client `list-unsubscribe` invocation.
 */
use function Safe\session_start;

try{
	session_start();

	/** @var NewsletterSubscription $newsletterSubscription The `NewsletterSubscription` for this request, passed in from the router. */
	$newsletterSubscription = $resource ?? throw new Exceptions\NewsletterSubscriptionNotFoundException();

	$newsletterName = $newsletterSubscription->Newsletter->Name;
	$newsletterSubscription->Delete();

	$_SESSION['is-newsletter-subscriptions-deleted'] = true;
	$_SESSION['newsletter-name'] = $newsletterName;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: ' . $newsletterSubscription->User->UuidUrl . '/newsletter-subscriptions');
}
catch(Exceptions\NewsletterSubscriptionNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
