<?
/**
 * POST		/webhooks/stripe/checkout.session.completed
 *
 * Record a completed Stripe subscription or one-time donation.
 */

$log = new Log();

try{
	$event = \Stripe\Webhook::constructEvent(Http::$Request->Body->RawBody, Http::$Request->Headers['stripe-signature'] ?? '', STRIPE_CHECKOUT_SESSION_COMPLETED_SIGNING_SECRET);

	$log->Queue($event->id . "\t" . 'Received Stripe ' . ($event->livemode ? '' : 'test') . ' event ' . $event->type . '.');

	if($event->type !== 'checkout.session.completed' || !isset($event->data->object) || !$event->data->object instanceof \Stripe\Checkout\Session){
		throw new Exceptions\WebhookException('Invalid Stripe event.');
	}

	$stripe = new \Stripe\StripeClient(['api_key' => STRIPE_API_KEY, 'stripe_version' => STRIPE_API_VERSION]);

	$checkoutSession = $stripe->checkout->sessions->retrieve($event->data->object->id, ['expand' => ['payment_intent.latest_charge.balance_transaction', 'subscription', 'customer']]);
	$paymentIntent = $checkoutSession->payment_intent;
	$customer = $checkoutSession->customer;

	if($checkoutSession->payment_status !== 'paid'){
		http_response_code(Enums\HttpCode::NoContent->value);
		return;
	}

	// Set our form's customer name after payment, keeping Stripe's billing details separate.
	/** @var string $name */
	$name = $checkoutSession->metadata['name'] ?? '';
	$name = trim($name);
	if($customer instanceof \Stripe\Customer && $name != '' && $customer->name !== $name){
		$stripe->customers->update($customer->id, ['name' => $name]);
	}

	if($checkoutSession->mode === 'subscription'){
		// Checkout can complete before its first invoice webhook, so record the subscription here.
		if(!$checkoutSession->subscription instanceof \Stripe\Subscription || !$customer instanceof \Stripe\Customer || $customer->email === null || !is_string($checkoutSession->metadata['name'] ?? null)){
			throw new Exceptions\WebhookException('Incomplete Stripe subscription data.');
		}

		// Validate the subscription before creating its local user.
		$subscription = Subscription::FromStripe($checkoutSession->subscription);

		try{
			$user = User::GetByEmail($customer->email);
		}
		catch(Exceptions\UserNotFoundException){
			$user = new User();
			$user->Email = $customer->email;
			$user->Name = $name;
			try{
				$user->Create();
			}
			catch(Exceptions\UserExistsException){
				$user = User::GetByEmail($customer->email);
			}
		}

		// Update the local name at signup even if the initial invoice webhook arrived first.
		if($name !== '' && $user->Name !== $name){
			Db::Query('update Users set Name = ? where UserId = ?', [$name, $user->UserId]);
			$user->Name = $name;
		}

		$subscription->UserId = $user->UserId;
		$subscription->Create();
		http_response_code(Enums\HttpCode::NoContent->value);
		return;
	}

	if($checkoutSession->mode !== 'payment' || ($checkoutSession->metadata['frequency'] ?? null) !== 'once'){
		http_response_code(Enums\HttpCode::NoContent->value);
		return;
	}

	if(
		!$paymentIntent instanceof \Stripe\PaymentIntent
		||
		!$customer instanceof \Stripe\Customer
	){
		throw new Exceptions\WebhookException('Incomplete Stripe data.');
	}

	$lineItems = $stripe->checkout->sessions->allLineItems($checkoutSession->id, ['limit' => 2]);
	if(sizeof($lineItems->data) !== 1 || $lineItems->has_more){
		throw new Exceptions\WebhookException('Incomplete Stripe line items.');
	}
	$isPatronsCircleProduct = $lineItems->data[0]->price?->product === STRIPE_PATRONS_CIRCLE_PRODUCT_ID;
	if(!$isPatronsCircleProduct && $lineItems->data[0]->price?->product !== STRIPE_DONATION_PRODUCT_ID){
		throw new Exceptions\WebhookException('Incomplete Stripe line items.');
	}

	$payment = Payment::FromStripeWebhook($paymentIntent, $checkoutSession, $customer);

	Db::Query('start transaction');

	try{
		$payment->Create();
		$payment->ProcessPatronsCircleBenefits(Enums\CycleType::Yearly, ($checkoutSession->metadata['is_anonymous'] ?? null) !== 'false', null, $isPatronsCircleProduct);
		Db::Query('commit');
	}
	catch(Exceptions\PaymentExistsException){
		Db::Query('rollback');
	}
	catch(Throwable $ex){
		Db::Query('rollback');
		throw $ex;
	}

	http_response_code(Enums\HttpCode::NoContent->value);
}
catch(Exceptions\SubscriptionInvalidException $ex){
	$log->Queue('Ignoring unsupported Stripe subscription: ' . $ex->getMessage());
	$log->WriteQueue();
	http_response_code(Enums\HttpCode::NoContent->value);
}
catch(\UnexpectedValueException | \Stripe\Exception\SignatureVerificationException | Exceptions\WebhookException $ex){
	$log->Queue('Invalid Stripe webhook: ' . $ex->getMessage());
	$log->WriteQueue();
	http_response_code(Enums\HttpCode::BadRequest->value);
}
catch(\Stripe\Exception\InvalidRequestException $ex){
	$log->Queue('Stripe webhook object retrieval failed with status ' . $ex->getHttpStatus() . '.');
	$log->WriteQueue();
	http_response_code($ex->getHttpStatus() == Enums\HttpCode::NotFound->value ? Enums\HttpCode::BadRequest->value : Enums\HttpCode::InternalServerError->value);
}
catch(\Stripe\Exception\ApiErrorException $ex){
	$log->Queue('Stripe webhook processing failed: Stripe API request failed with status ' . $ex->getHttpStatus() . '.');
	$log->WriteQueue();
	http_response_code(Enums\HttpCode::InternalServerError->value);
}
catch(Throwable $ex){
	$log->Queue('Stripe webhook processing failed: ' . $ex->getMessage());
	$log->WriteQueue();
	http_response_code(Enums\HttpCode::InternalServerError->value);
}
