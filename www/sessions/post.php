<?
use function Safe\session_unset;

try{
	HttpInput::ValidateRequestMethod([HttpMethod::Post]);

	session_start();

	$requestType = HttpInput::RequestType();

	$session = new Session();
	$email = HttpInput::Str(POST, 'email');
	$password = HttpInput::Str(POST, 'password');
	$redirect = HttpInput::Str(POST, 'redirect');

	if($redirect === null){
		$redirect = '/';
	}

	$session->Create($email, $password);

	session_unset();

	if($requestType == HttpRequestType::Web){
		http_response_code(303);
		header('Location: ' . $redirect);
	}
	else{
		// Access via HttpRequestType::Rest api; 201 CREATED with location
		http_response_code(201);
		header('Location: ' . $session->Url);
	}
}
catch(Exceptions\InvalidLoginException | Exceptions\PasswordRequiredException $ex){
	if($requestType == HttpRequestType::Web){
		$_SESSION['email'] = $email;
		$_SESSION['redirect'] = $redirect;
		$_SESSION['exception'] = $ex;

		// Access via form; 303 redirect to the form, which will emit a 422 Unprocessable Entity
		http_response_code(303);
		header('Location: /sessions/new');
	}
	else{
		// Access via HttpRequestType::Rest api; 422 Unprocessable Entity
		http_response_code(422);
	}
}
