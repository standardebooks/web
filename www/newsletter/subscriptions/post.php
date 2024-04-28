<?
use Ramsey\Uuid\Uuid;
use function Safe\session_unset;

if(HttpInput::RequestMethod() != HTTP_POST){
	http_response_code(405);
	exit();
}

session_start();

$requestType = HttpInput::RequestType();

$subscription = new NewsletterSubscription();

if(HttpInput::Str(POST, 'automationtest')){
	// A bot filled out this form field, which should always be empty. Pretend like we succeeded.
	if($requestType == WEB){
		http_response_code(303);
		$uuid = Uuid::uuid4();
		$subscription->User = new User();
		$subscription->User->Uuid = $uuid->toString();
		$_SESSION['subscription-created'] = 0; // 0 means 'bot'
		header('Location: /newsletter/subscriptions/success');
	}
	else{
		// Access via REST api; 201 CREATED with location
		http_response_code(201);
		header('Location: /newsletter/subscriptions/success');
	}

	exit();
}

try{
	$subscription->User = new User();
	$subscription->User->Email = HttpInput::Str(POST, 'email');
	$subscription->IsSubscribedToNewsletter = HttpInput::Bool(POST, 'issubscribedtonewsletter') ?? false;
	$subscription->IsSubscribedToSummary = HttpInput::Bool(POST, 'issubscribedtosummary') ?? false;

	$expectedCaptcha = HttpInput::Str(SESSION, 'captcha') ?? '';
	$receivedCaptcha = HttpInput::Str(POST, 'captcha');

	$subscription->Create($expectedCaptcha, $receivedCaptcha);

	session_unset();

	if($requestType == WEB){
		http_response_code(303);
		$_SESSION['subscription-created'] = $subscription->UserId;
		header('Location: /newsletter/subscriptions/success');
	}
	else{
		// Access via REST api; 201 CREATED with location
		http_response_code(201);
		header('Location: /newsletter/subscriptions/success');
	}
}
catch(Exceptions\NewsletterSubscriptionExistsException){
	// Subscription exists.
	if($requestType == WEB){
		// If we're accessing from the web, update the subscription,
		// re-sending the confirmation email if the user isn't yet confirmed
		$existingSubscription = NewsletterSubscription::Get($subscription->User->Uuid);
		$subscription->IsConfirmed = $existingSubscription->IsConfirmed;
		$subscription->Save();

		http_response_code(303);

		if(!$subscription->IsConfirmed){
			// Don't re-send the email after all, to prevent spam
			// $subscription->SendConfirmationEmail();

			header('Location: /newsletter/subscriptions/success');
		}
		else{
			$_SESSION['subscription-updated'] = $subscription->UserId;
			header('Location: ' . $subscription->Url);
		}
	}
	else{
		// Access via REST api; 409 CONFLICT
		http_response_code(409);
	}
}
catch(Exceptions\InvalidNewsletterSubscription $ex){
	if($requestType == WEB){
		$_SESSION['subscription'] = $subscription;
		$_SESSION['exception'] = $ex;

		// Access via form; 303 redirect to the form, which will emit a 422 Unprocessable Entity
		http_response_code(303);
		header('Location: /newsletter/subscriptions/new');
	}
	else{
		// Access via REST api; 422 Unprocessable Entity
		http_response_code(422);
	}
}
