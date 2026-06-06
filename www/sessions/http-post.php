<?
/**
 * POST		/sessions
 */

use function Safe\session_start;
use function Safe\session_unset;

try{
	session_start();

	$session = new Session();
	$email = Http::$Request->Body->Get('email');
	$password = Http::$Request->Body->Get('password');
	$redirect = Template::SanitizeRedirectUrl(Http::$Request->Body->Get('redirect'));

	$session->Create($email, $password);

	session_unset();

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: ' . $redirect);
}
catch(Exceptions\LoginInvalidException | Exceptions\PasswordRequiredException $ex){
	$_SESSION['email'] = $email;
	$_SESSION['redirect'] = $redirect;
	$_SESSION['session/create/exception'] = $ex;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: /sessions/new');
}
