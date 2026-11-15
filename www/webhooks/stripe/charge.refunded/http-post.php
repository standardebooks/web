<?
/**
 * POST		/webhooks/stripe/charge.refunded
 *
 * Record the first refund of a Stripe payment.
 */

$log = new Log();

try{
	$event = \Stripe\Webhook::constructEvent(Http::$Request->Body->RawBody, Http::$Request->Headers['stripe-signature'] ?? '', STRIPE_CHARGE_REFUNDED_SIGNING_SECRET);
	$log->Queue($event->id . "\t" . 'Received Stripe event ' . $event->type . '.');

	if($event->type !== 'charge.refunded' || !$event->data->object instanceof \Stripe\Charge){
		throw new Exceptions\WebhookException('Invalid Stripe event.');
	}

	// Recording only the first refund, including a first partial refund.
	if(($event->data->previous_attributes['amount_refunded'] ?? null) !== 0){
		http_response_code(Enums\HttpCode::NoContent->value);
		return;
	}

	$charge = $event->data->object;
	if(!is_string($charge->payment_intent)){
		throw new Exceptions\WebhookException('Stripe refund has no PaymentIntent ID.');
	}

	// Keep the event retryable if it arrives before Checkout or invoice fulfillment records the payment.
	if(!Db::QueryBool('select exists(select * from Payments where Processor = ? and TransactionId = ?)', [Enums\PaymentProcessorType::Stripe, $charge->payment_intent])){
		throw new Exceptions\PaymentNotFoundException('Stripe payment has not been recorded yet: ' . $charge->payment_intent);
	}

	// Reset the export flag so the accounting line item exporter picks up the refund as a debit. Duplicate events must not queue it again.
	Db::Query('update Payments set RefundedAt = ?, IsRecordedExternally = false where Processor = ? and TransactionId = ? and RefundedAt is null', [NOW, Enums\PaymentProcessorType::Stripe, $charge->payment_intent]);
	$log->Queue('Transaction ' . $charge->payment_intent . ' refund recorded or already processed.');
	http_response_code(Enums\HttpCode::NoContent->value);
}
catch(\UnexpectedValueException | \Stripe\Exception\SignatureVerificationException | Exceptions\WebhookException $ex){
	$log->Queue('Invalid Stripe webhook: ' . $ex->getMessage());
	http_response_code(Enums\HttpCode::BadRequest->value);
}
catch(Throwable $ex){
	$log->Queue('Stripe webhook processing failed: ' . $ex->getMessage());
	http_response_code(Enums\HttpCode::InternalServerError->value);
}
finally{
	$log->WriteQueue();
}
