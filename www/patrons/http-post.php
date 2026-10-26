<?
/**
 * POST		/users/:user-identifier/patrons
 */

use function Safe\session_start;

try{
	session_start();

	/** @var User $user The `User` for this request, passed in from the router. */
	$user = $resource ?? throw new Exceptions\UserNotFoundException();

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditUsers){
		throw new Exceptions\PermissionsInvalidException();
	}

	$payment = new Payment();
	$payment->FillFromRequestBody();
	$payment->UserId = $user->UserId;
	$payment->User = $user;

	$patron = new Patron();
	$patron->FillFromRequestBody();
	$patron->UserId = $user->UserId;
	$patron->User = $user;

	$payment->Validate();
	$patron->Validate();

	Db::Transaction(function() use ($payment, $patron, $user): void{
		// Clone the submitted objects so each attempt starts without rolled-back state.
		$payment = clone $payment;
		$patron = clone $patron;
		$user = clone $user;
		$payment->User = $user;
		$patron->User = $user;

		$existingPayment = Db::Query('
			select *
			from Payments
			where TransactionId = ?
			for update
		', [$payment->TransactionId], Payment::class)[0] ?? null;

		if($existingPayment !== null){
			if($existingPayment->Processor !== $payment->Processor){
				throw new Exceptions\PaymentExistsException();
			}

			$payment->PaymentId = $existingPayment->PaymentId;
			$payment->SubscriptionId = $existingPayment->SubscriptionId;
			$payment->RefundedAt = $existingPayment->RefundedAt;

			if($payment->IsRecurring && $payment->Processor === Enums\PaymentProcessorType::FracturedAtlas){
				// Reuse this user's active Fractured Atlas subscription, or create one from the submitted payment.
				$subscription = Db::Query('
					select * from Subscriptions
					where UserId = ? and Processor = ? and CycleType = ? and EndedAt is null
					order by CreatedAt desc, SubscriptionId desc
					limit 1
				', [$user->UserId, $payment->Processor, Enums\CycleType::Monthly], Subscription::class)[0] ?? null;

				if($subscription === null){
					$subscription = new Subscription();
					$subscription->UserId = $user->UserId;
					$subscription->CreatedAt = $payment->CreatedAt;
					$subscription->Processor = $payment->Processor;
					$subscription->Amount = $payment->Amount;
					$subscription->CycleType = Enums\CycleType::Monthly;
					$subscription->Create();
				}

				$payment->SubscriptionId = $subscription->SubscriptionId;
				$payment->Subscription = $subscription;
			}
			elseif(!$payment->IsRecurring){
				$payment->SubscriptionId = null;
			}

			// Update the anonymous payment with the form data instead of recording the donation twice.
			Db::Query('
				update Payments set UserId = ?, SubscriptionId = ?, CreatedAt = ?, Amount = ?, Fee = ?, IsRecurring = ?, IsMatchingDonation = ?
				where PaymentId = ?
			', [$payment->UserId, $payment->SubscriptionId, $payment->CreatedAt, $payment->Amount, $payment->Fee, $payment->IsRecurring, $payment->IsMatchingDonation, $payment->PaymentId]);
		}
		else{
			$payment->Create();
		}

		$patron->SubscriptionId = null;
		$patron->QualifyingPaymentId = $payment->PaymentId;
		if($payment->IsRecurring){
			$patron->SubscriptionId = $payment->SubscriptionId;
			$patron->QualifyingPaymentId = null;
		}
		$patron->CreatedAt = $payment->CreatedAt;
		$patron->Validate(payment: $payment);
		$patron->Create(sendWelcomeEmail: false);
	});

	$_SESSION['patron/create/is-created'] = true;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: ' . $user->Url);
}
catch(Exceptions\UserNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\PermissionsInvalidException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
catch(Exceptions\PaymentExistsException | Exceptions\ValidationException $ex){
	$_SESSION['patron/create/payment'] = $payment;
	$_SESSION['patron/create/patron'] = $patron;
	$_SESSION['patron/create/exception'] = $ex;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: ' . $user->Url . '/patrons/new');
}
