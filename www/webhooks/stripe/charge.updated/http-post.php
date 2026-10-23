<?
/**
 * POST		/webhooks/stripe/charge.updated
 *
 * Update a recorded Stripe payment's fee after asynchronous capture supplies its balance transaction.
 */

$log = new Log();

try{
	$event = \Stripe\Webhook::constructEvent(Http::$Request->Body->RawBody, Http::$Request->Headers['stripe-signature'] ?? '', STRIPE_CHARGE_UPDATED_SIGNING_SECRET);
	$log->Queue($event->id . "\t" . 'Received Stripe ' . ($event->livemode ? '' : 'test') . ' event ' . $event->type . '.');

	if($event->type !== 'charge.updated' || !$event->data->object instanceof \Stripe\Charge){
		throw new Exceptions\WebhookException('Invalid Stripe event.');
	}

	// Retrieve current data so a delayed event uses the latest balance transaction and fee.
	$stripe = new \Stripe\StripeClient(['api_key' => STRIPE_API_KEY, 'stripe_version' => STRIPE_API_VERSION]);
	$charge = $stripe->charges->retrieve($event->data->object->id, ['expand' => ['balance_transaction']]);
	if(!$charge->paid || $charge->status !== 'succeeded' || $charge->currency !== 'usd' || !is_string($charge->payment_intent) || !$charge->balance_transaction instanceof \Stripe\BalanceTransaction){
		http_response_code(Enums\HttpCode::NoContent->value);
		return;
	}

	// Stripe can deliver this event before Checkout or invoice fulfillment. Keep it retryable until the payment exists.
	if(!Db::QueryBool('select exists(select * from Payments where Processor = ? and TransactionId = ?)', [Enums\PaymentProcessorType::Stripe, $charge->payment_intent])){
		throw new Exceptions\PaymentNotFoundException('Stripe payment has not been recorded yet: ' . $charge->payment_intent);
	}

	Db::Query('update Payments set Fee = ? where Processor = ? and TransactionId = ?', [$charge->balance_transaction->fee / 100, Enums\PaymentProcessorType::Stripe, $charge->payment_intent]);
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
