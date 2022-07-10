<?
require_once('Core.php');

use Ramsey\Uuid\Uuid;
use function Safe\session_unset;

if(HttpInput::RequestMethod() != HTTP_POST){
	http_response_code(405);
	exit();
}

session_start();

$requestType = HttpInput::RequestType();

$subscription = new NewsletterSubscription();

if(HttpInput::Str(POST, 'automationtest', false)){
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
	$subscription->User->Email = HttpInput::Str(POST, 'email', false);
	$subscription->IsSubscribedToNewsletter = HttpInput::Bool(POST, 'issubscribedtonewsletter', false);
	$subscription->IsSubscribedToSummary = HttpInput::Bool(POST, 'issubscribedtosummary', false);

	$captcha = HttpInput::Str(SESSION, 'captcha', false) ?? '';

	$exception = new Exceptions\ValidationException();

	try{
		$subscription->Validate();
	}
	catch(Exceptions\ValidationException $ex){
		$exception->Add($ex);
	}

	if($captcha === '' || mb_strtolower($captcha) !== mb_strtolower(HttpInput::Str(POST, 'captcha', false) ?? '')){
		$exception->Add(new Exceptions\InvalidCaptchaException());
	}

	if($exception->HasExceptions){
		throw $exception;
	}

	$subscription->Create();

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
catch(Exceptions\NewsletterSubscriptionExistsException $ex){
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
catch(Exceptions\SeException $ex){
	// Validation failed
	if($requestType == WEB){
		$_SESSION['subscription'] = $subscription;
		$_SESSION['exception'] = $ex;

		// Access via form; 303 redirect to the form, which will emit a 400 BAD REQUEST
		http_response_code(303);
		header('Location: /newsletter/subscriptions/new');
	}
	else{
		// Access via REST api; 400 BAD REQUEST
		http_response_code(400);
	}
}
