<?
/**
 * GET /users/<uuid>/newsletter-subscriptions/confirm
 * Confirm *all* pending `NewsletterSubscriptions` for the given `User`.
 */

use function Safe\session_start;

session_start();

try{
	// Note: Only allow UUIDs, not *any* identifier, because we don't want to be able to confirm a `User`'s subscription by passing in the email address we already know!
	$user = User::GetByUuid(HttpInput::Str(GET, 'user-identifier'));

	$user->ConfirmNewsletterSubscriptions();

	$_SESSION['are-newsletter-subscriptions-confirmed'] = true;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('Location: /users/' . $user->Uuid . '/newsletter-subscriptions');
}
catch(Exceptions\UserNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
