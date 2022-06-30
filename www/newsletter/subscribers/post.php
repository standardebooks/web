<?
require_once('Core.php');

use function Safe\preg_match;
use function Safe\session_unset;

if(HttpInput::RequestMethod() != HTTP_POST){
	http_response_code(405);
	exit();
}

session_start();

$requestType = HttpInput::RequestType();

$subscriber = new NewsletterSubscriber();

if(HttpInput::Str(POST, 'automationtest', false)){
	// A bot filled out this form field, which should always be empty. Pretend like we succeeded.
	if($requestType == WEB){
		http_response_code(303);
		header('Location: /newsletter/subscribers/success');
	}
	else{
		// Access via REST api; 201 CREATED with location
		http_response_code(201);
		header('Location: ' . $subscriber->Url);
	}
	exit();
}

try{
	$subscriber->FirstName = HttpInput::Str(POST, 'firstname', false);
	$subscriber->LastName = HttpInput::Str(POST, 'lastname', false);
	$subscriber->Email = HttpInput::Str(POST, 'email', false);
	$subscriber->IsSubscribedToNewsletter = HttpInput::Bool(POST, 'newsletter', false);
	$subscriber->IsSubscribedToSummary = HttpInput::Bool(POST, 'monthlysummary', false);

	$captcha = $_SESSION['captcha'] ?? '';

	if($captcha === '' || mb_strtolower($captcha) !== mb_strtolower(HttpInput::Str(POST, 'captcha', false) ?? '')){
		throw new Exceptions\ValidationException(new Exceptions\InvalidCaptchaException());
	}

	$subscriber->Create();

	session_unset();

	if($requestType == WEB){
		http_response_code(303);
		header('Location: /newsletter/subscribers/success');
	}
	else{
		// Access via REST api; 201 CREATED with location
		http_response_code(201);
		header('Location: ' . $subscriber->Url);
	}
}
catch(Exceptions\SeException $ex){
	// Validation failed
	if($requestType == WEB){
		$_SESSION['subscriber'] = $subscriber;
		$_SESSION['exception'] = $ex;

		// Access via form; 303 redirect to the form, which will emit a 400 BAD REQUEST
		http_response_code(303);
		header('Location: /newsletter/subscribers/new');
	}
	else{
		// Access via REST api; 400 BAD REQUEST
		http_response_code(400);
	}
}
