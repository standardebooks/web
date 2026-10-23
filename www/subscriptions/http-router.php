<?
/**
 * DELETE	/users/:user-identifier/subscriptions/:subscription-id
 */

try{
	$identifier = Http::$Request->QueryString->Get('user-identifier');
	$user = User::GetByIdentifier($identifier);

	$subscription = Subscription::Get(Http::$Request->QueryString->Get('subscription-id', 'int'));

	if($subscription->UserId != $user->UserId){
		throw new Exceptions\SubscriptionNotFoundException();
	}

	Http::$Request->Route(resource: $subscription);
}
catch(Exceptions\UserNotFoundException | Exceptions\SubscriptionNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\AmbiguousUserException){
	Template::RedirectToDisambiguation($identifier);
}
