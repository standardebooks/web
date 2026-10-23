<?
/**
 * GET		/users/:user-identifier/subscriptions/:subscription-id/edit
 */

try{
	$identifier = Http::$Request->QueryString->Get('user-identifier');
	$user = User::GetByIdentifier($identifier);
	$subscription = Subscription::Get(Http::$Request->QueryString->Get('subscription-id', 'int'));

	if($subscription->UserId != $user->UserId || $subscription->EndedAt !== null || $subscription->ProcessorSubscriptionId === null){
		throw new Exceptions\SubscriptionNotFoundException();
	}

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(Session::$User->UserId !== $user->UserId){
		throw new Exceptions\PermissionsInvalidException();
	}

	switch($subscription->Processor){
		case Enums\PaymentProcessorType::FracturedAtlas:
			header('location: /help/managing-your-recurring-donation');
			break;

		case Enums\PaymentProcessorType::Stripe:
			$stripe = new \Stripe\StripeClient(['api_key' => STRIPE_API_KEY, 'stripe_version' => STRIPE_API_VERSION]);

			$stripeSubscription = $stripe->subscriptions->retrieve($subscription->ProcessorSubscriptionId);

			$customer = $stripeSubscription->customer;

			if(!is_string($customer)){
				$customer = $customer->id;
			}

			$portalSession = $stripe->billingPortal->sessions->create([
				'customer' => $customer,
				'flow_data' => [
					'type' => 'payment_method_update',
					'after_completion' => ['type' => 'redirect', 'redirect' => ['return_url' => SITE_URL . $user->Url . '?is-subscription-edited']],
				],
				'return_url' => SITE_URL . $user->Url,
			]);

			http_response_code(Enums\HttpCode::SeeOther->value);
			header('location: ' . $portalSession->url);
			break;

		default:
			throw new Exceptions\PermissionsInvalidException();
	}
}
catch(Exceptions\UserNotFoundException | Exceptions\SubscriptionNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\AmbiguousUserException){
	Template::RedirectToDisambiguation($identifier);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\PermissionsInvalidException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
catch(\Stripe\Exception\ApiErrorException $ex){
	(new Log())->Write('Stripe billing settings request failed: ' . $ex->getMessage());
	Template::ExitWithCode(Enums\HttpCode::BadGateway, new Exceptions\StripeApiException('We couldn’t open your billing settings. Please try again later.'));
}
