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

	Db::Query('start transaction');
	try{
		$existingPayment = Db::Query('
			select *
			from Payments
			where TransactionId = ?
			for update
		', [$payment->TransactionId], Payment::class)[0] ?? null;

		if($existingPayment !== null){
			// Assign the existing payment to this user instead of recording the transaction twice.
			Db::Query('update Payments set UserId = ? where PaymentId = ?', [$user->UserId, $existingPayment->PaymentId]);
			$payment = $existingPayment;
		}
		else{
			$payment->Create();
		}

		$patron->CycleType = $payment->IsRecurring ? Enums\CycleType::Monthly : Enums\CycleType::Yearly;
		$patron->CreatedAt = $payment->CreatedAt;
		$patron->BaseCost = match($patron->CycleType){
			Enums\CycleType::Monthly => PATRONS_CIRCLE_MONTHLY_COST,
			Enums\CycleType::Yearly => PATRONS_CIRCLE_YEARLY_COST,
		};
		$patron->Create(sendWelcomeEmail: false);
		Db::Query('commit');
	}
	catch(\Throwable $ex){
		Db::Query('rollback');

		throw $ex;
	}

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
catch(Exceptions\PaymentExistsException $ex){
	$_SESSION['patron/create/payment'] = $payment;
	$_SESSION['patron/create/patron'] = $patron;
	$_SESSION['patron/create/exception'] = $ex;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: ' . $user->Url . '/patrons/new');
}
