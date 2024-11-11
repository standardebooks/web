<?
use Ramsey\Uuid\Uuid;
use function Safe\session_unset;

try{
	HttpInput::ValidateRequestMethod([Enums\HttpMethod::Post]);

	session_start();

	$requestType = HttpInput::GetRequestType();

	$subscription = new NewsletterSubscription();

	if(HttpInput::Str(POST, 'automationtest')){
		// A bot filled out this form field, which should always be empty. Pretend like we succeeded.
		if($requestType == Enums\HttpRequestType::Web){
			http_response_code(303);
			$uuid = Uuid::uuid4();
			$subscription->User = new User();
			$subscription->User->Uuid = $uuid->toString();
			$_SESSION['is-subscription-created'] = 0; // 0 means 'bot'
			header('Location: /newsletter/subscriptions/success');
		}
		else{
			// Access via Enums\HttpRequestType::Rest api; 201 CREATED with location
			http_response_code(201);
			header('Location: /newsletter/subscriptions/success');
		}

		exit();
	}

	$subscription->User = new User();
	$subscription->User->Email = HttpInput::Str(POST, 'email');
	$subscription->IsSubscribedToNewsletter = HttpInput::Bool(POST, 'issubscribedtonewsletter') ?? false;
	$subscription->IsSubscribedToSummary = HttpInput::Bool(POST, 'issubscribedtosummary') ?? false;

	$expectedCaptcha = HttpInput::Str(SESSION, 'captcha') ?? '';
	$receivedCaptcha = HttpInput::Str(POST, 'captcha');

	$subscription->Create($expectedCaptcha, $receivedCaptcha);

	session_unset();

	if($requestType == Enums\HttpRequestType::Web){
		http_response_code(303);
		$_SESSION['is-subscription-created'] = $subscription->UserId;
		header('Location: /newsletter/subscriptions/success');
	}
	else{
		// Access via Enums\HttpRequestType::Rest api; 201 CREATED with location
		http_response_code(201);
		header('Location: /newsletter/subscriptions/success');
	}
}
catch(Exceptions\NewsletterSubscriptionExistsException){
	// Subscription exists.
	if($requestType == Enums\HttpRequestType::Web){
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
		// Access via Enums\HttpRequestType::Rest api; 409 CONFLICT
		http_response_code(409);
	}
}
catch(Exceptions\InvalidNewsletterSubscription $ex){
	if($requestType == Enums\HttpRequestType::Web){
		$_SESSION['subscription'] = $subscription;
		$_SESSION['exception'] = $ex;

		// Access via form; 303 redirect to the form, which will emit a 422 Unprocessable Entity
		http_response_code(303);
		header('Location: /newsletter/subscriptions/new');
	}
	else{
		// Access via Enums\HttpRequestType::Rest api; 422 Unprocessable Entity
		http_response_code(422);
	}
}
