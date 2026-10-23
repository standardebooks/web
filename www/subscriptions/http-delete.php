<?
/**
 * DELETE	/users/:user-identifier/subscriptions/:subscription-id
 */

use function Safe\session_start;

try{
	session_start();

	/** @var Subscription $resource The `Subscription` passed in from the router. */
	$subscription = $resource;

	if($subscription->ProcessorSubscriptionId === null){
		Template::ExitWithCode(Enums\HttpCode::NotFound);
	}

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(Session::$User->UserId !== $subscription->User->UserId && !Session::$User->Benefits->CanEditUsers){
		throw new Exceptions\PermissionsInvalidException();
	}

	$subscription->Cancel();

	$_SESSION['subscription/delete/is-deleted'] = true;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: ' . $subscription->User->Url);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\PermissionsInvalidException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
catch(Exceptions\StripeApiException $ex){
	(new Log())->Write('Stripe subscription cancellation failed: ' . ($ex->getPrevious() ?? $ex)->getMessage());
	Template::ExitWithCode(Enums\HttpCode::BadGateway, new Exceptions\StripeApiException('We couldn’t cancel your recurring donation. Please try again later.'));
}
