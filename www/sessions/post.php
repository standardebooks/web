<?
use function Safe\session_unset;

try{
	HttpInput::ValidateRequestMethod([Enums\HttpMethod::Post]);

	session_start();

	$requestType = HttpInput::GetRequestType();

	$session = new Session();
	$email = HttpInput::Str(POST, 'email');
	$password = HttpInput::Str(POST, 'password');
	$redirect = HttpInput::Str(POST, 'redirect');

	if($redirect === null){
		$redirect = '/';
	}

	$session->Create($email, $password);

	session_unset();

	if($requestType == Enums\HttpRequestType::Web){
		http_response_code(Enums\HttpCode::SeeOther->value);
		header('Location: ' . $redirect);
	}
	else{
		// Access via REST API; output `201 Created` with location.
		http_response_code(Enums\HttpCode::Created->value);
		header('Location: ' . $session->Url);
	}
}
catch(Exceptions\InvalidLoginException | Exceptions\PasswordRequiredException $ex){
	if($requestType == Enums\HttpRequestType::Web){
		$_SESSION['email'] = $email;
		$_SESSION['redirect'] = $redirect;
		$_SESSION['exception'] = $ex;

		// Access via form; 303 redirect to the form, which will emit a 422 Unprocessable Entity
		http_response_code(Enums\HttpCode::SeeOther->value);
		header('Location: /sessions/new');
	}
	else{
		// Access via Enums\HttpRequestType::Rest api; 422 Unprocessable Entity
		http_response_code(Enums\HttpCode::UnprocessableContent->value);
	}
}
