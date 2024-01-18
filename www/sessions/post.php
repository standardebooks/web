<?
use function Safe\preg_match;
use function Safe\session_unset;

if(HttpInput::RequestMethod() != HTTP_POST){
	http_response_code(405);
	exit();
}

session_start();

$requestType = HttpInput::RequestType();

$session = new Session();
$email = HttpInput::Str(POST, 'email');
$password = HttpInput::Str(POST, 'password');
$redirect = HttpInput::Str(POST, 'redirect');

try{
	if($redirect === null){
		$redirect = '/';
	}

	$session->Create($email, $password);

	if($requestType == WEB){
		http_response_code(303);
		header('Location: ' . $redirect);
	}
	else{
		// Access via REST api; 201 CREATED with location
		http_response_code(201);
		header('Location: ' . $session->Url);
	}
}
catch(Exceptions\AppException $ex){
	// Login failed
	if($requestType == WEB){
		$_SESSION['email'] = $email;
		$_SESSION['redirect'] = $redirect;
		$_SESSION['exception'] = $ex;

		// Access via form; 303 redirect to the form, which will emit a 422 Unprocessable Entity
		http_response_code(303);
		header('Location: /sessions/new');
	}
	else{
		// Access via REST api; 422 Unprocessable Entity
		http_response_code(422);
	}
}
