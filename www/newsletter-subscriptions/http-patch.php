<?
/**
 * PATCH	/users/:uuid/newsletter-subscriptions/:newsletter-id
 *
 * Confirm *all* pending `NewsletterSubscriptions` for the given `User`. In theory, we would include a parameter like `newsletter-subscription-is-confirmed=true`, but since the only thing we can patch in a `NewsletterSubscription` is setting `IsConfirmed` to **`TRUE`**, we omit it for brevity.
 */

use function Safe\session_start;

try{
	session_start();

	// Note: Only allow UUIDs, not *any* identifier, because we don't want to be able to confirm a `User`'s subscription by passing in the email address we already know!
	/** @var NewsletterSubscription $newsletterSubscription The `NewsletterSubscription` for this request, passed in from the router. */
	$newsletterSubscription = $resource ?? throw new Exceptions\NewsletterSubscriptionNotFoundException();

	$newsletterSubscription->User->ConfirmNewsletterSubscriptions();

	$_SESSION['are-newsletter-subscriptions-confirmed'] = true;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: ' . $newsletterSubscription->User->UuidUrl . '/newsletter-subscriptions');
}
catch(Exceptions\NewsletterSubscriptionNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
