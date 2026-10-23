<?
/**
 * POST		/webhooks/stripe/customer.subscription.deleted
 *
 * End a Stripe subscription when Stripe reports its cancellation.
 */

$log = new Log();

try{
	$event = \Stripe\Webhook::constructEvent(Http::$Request->Body->RawBody, Http::$Request->Headers['stripe-signature'] ?? '', STRIPE_CUSTOMER_SUBSCRIPTION_DELETED_SIGNING_SECRET);
	$log->Queue($event->id . "\t" . 'Received Stripe ' . ($event->livemode ? '' : 'test') . ' event ' . $event->type . '.');

	if($event->type !== 'customer.subscription.deleted' || !isset($event->data->object) || !$event->data->object instanceof \Stripe\Subscription){
		throw new Exceptions\WebhookException('Invalid Stripe event.');
	}

	$stripeSubscription = $event->data->object;
	$endedAt = $stripeSubscription->ended_at === null ? NOW : new \Safe\DateTimeImmutable('@' . $stripeSubscription->ended_at);
	Db::Query('update Subscriptions set EndedAt = ?, NextPaymentAt = null where Processor = ? and ProcessorSubscriptionId = ?', [$endedAt, Enums\PaymentProcessorType::Stripe, $stripeSubscription->id]);

	http_response_code(Enums\HttpCode::NoContent->value);
}
catch(\UnexpectedValueException | \Stripe\Exception\SignatureVerificationException | Exceptions\WebhookException $ex){
	$log->Queue('Invalid Stripe webhook: ' . $ex->getMessage());
	$log->WriteQueue();
	http_response_code(Enums\HttpCode::BadRequest->value);
}
catch(Throwable $ex){
	$log->Queue('Stripe webhook processing failed: ' . $ex->getMessage());
	$log->WriteQueue();
	http_response_code(Enums\HttpCode::InternalServerError->value);
}
