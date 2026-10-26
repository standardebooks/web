<?
/**
 * POST		/webhooks/stripe/invoice.payment_succeeded
 *
 * Record a successful recurring Stripe donation.
 */

use Safe\DateTimeImmutable;

$log = new Log();

try{
	$event = \Stripe\Webhook::constructEvent(Http::$Request->Body->RawBody, Http::$Request->Headers['stripe-signature'] ?? '', STRIPE_INVOICE_PAYMENT_SUCCEEDED_SIGNING_SECRET);

	$log->Queue($event->id . "\t" . 'Received Stripe ' . ($event->livemode ? '' : 'test') . ' event ' . $event->type . '.');

	if($event->type !== 'invoice.payment_succeeded' || !isset($event->data->object) || !$event->data->object instanceof \Stripe\Invoice){
		throw new Exceptions\WebhookException('Invalid Stripe event.');
	}

	$stripe = new \Stripe\StripeClient(['api_key' => STRIPE_API_KEY, 'stripe_version' => STRIPE_API_VERSION]);

	$invoice = $stripe->invoices->retrieve($event->data->object->id, ['expand' => ['payments', 'parent.subscription_details.subscription', 'customer']]);
	$stripeSubscription = $invoice->parent?->type === 'subscription_details' ? $invoice->parent->subscription_details?->subscription : null;
	$customer = $invoice->customer;

	if(
		$invoice->status !== 'paid'
		||
		$invoice->amount_paid <= 0
		||
		!$stripeSubscription instanceof \Stripe\Subscription
	){
		http_response_code(Enums\HttpCode::NoContent->value);
		return;
	}

	if(!$customer instanceof \Stripe\Customer || $invoice->payments === null){
		throw new Exceptions\WebhookException('Incomplete Stripe data.');
	}

	// Reject unsupported subscriptions before creating any local payment or user.
	$subscription = Subscription::FromStripe($stripeSubscription);

	// An invoice can have multiple payments, so record each successful PaymentIntent separately.
	foreach($invoice->payments->autoPagingIterator() as $invoicePayment){
		if($invoicePayment->status !== 'paid' || $invoicePayment->amount_paid <= 0 || $invoicePayment->payment->type !== 'payment_intent'){
			continue;
		}

		if(!isset($invoicePayment->payment->payment_intent) || !is_string($invoicePayment->payment->payment_intent) || $invoicePayment->status_transitions->paid_at === null){
			throw new Exceptions\WebhookException('Incomplete Stripe data.');
		}

		$paymentIntent = $stripe->paymentIntents->retrieve($invoicePayment->payment->payment_intent, ['expand' => ['latest_charge.balance_transaction']]);

		$payment = Payment::FromStripeWebhook($paymentIntent, $stripeSubscription, $customer);
		$payment->CreatedAt = new DateTimeImmutable('@' . $invoicePayment->status_transitions->paid_at);

		try{
			Db::Transaction(function() use ($payment, $subscription, $stripeSubscription): void{
				// Clone the payment, user, and subscription before each attempt can change them.
				$payment = clone $payment;
				if($payment->User !== null){
					$payment->User = clone $payment->User;
				}
				$subscription = clone $subscription;

				$payment->Create();
				if($payment->UserId === null){
					throw new Exceptions\WebhookException('Stripe recurring payment has no user.');
				}
				$subscription->UserId = $payment->UserId;
				$subscription->Create();
				$payment->SubscriptionId = $subscription->SubscriptionId;
				Db::Query('update Payments set SubscriptionId = ? where PaymentId = ?', [$payment->SubscriptionId, $payment->PaymentId]);
				$isPatronsCircleProduct = $stripeSubscription->items->data[0]->price->product === STRIPE_PATRONS_CIRCLE_PRODUCT_ID;
				$payment->ProcessPatronsCircleBenefits($subscription->CycleType, ($stripeSubscription->metadata['is_anonymous'] ?? null) !== 'false', null, $isPatronsCircleProduct);
			});
		}
		catch(Exceptions\PaymentExistsException){
			// The transaction was rolled back because this payment was already recorded.
		}
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
