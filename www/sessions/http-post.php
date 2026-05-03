<?
/**
 * POST		/sessions
 */

use function Safe\session_start;
use function Safe\session_unset;

try{
	session_start();

	$session = new Session();
	$email = HttpInput::Str(POST, 'email');
	$password = HttpInput::Str(POST, 'password');
	$redirect = Template::SanitizeRedirectUrl(HttpInput::Str(POST, 'redirect'));

	$session->Create($email, $password);

	session_unset();

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: ' . $redirect);
}
catch(Exceptions\InvalidLoginException | Exceptions\PasswordRequiredException $ex){
	$_SESSION['email'] = $email;
	$_SESSION['redirect'] = $redirect;
	$_SESSION['exception'] = $ex;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: /sessions/new');
}
