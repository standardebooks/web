<?
/**
 * PATCH	/users/:user-identifier/session-login-codes/:session-login-code
 */

try{
	/** @var User $user The `User` for this request, passed in from the router. */
	$user = $resource ?? throw new Exceptions\UserNotFoundException();

	$sessionLoginCode = SessionLoginCode::Get(Http::$Request->QueryString->Get('session-login-code'), $user->UserId);
	Session::InitializeFromCode($sessionLoginCode);

	http_response_code(Enums\HttpCode::SeeOther->value);

	header('location: ' . Template::SanitizeRedirectUrl(Http::$Request->QueryString->Get('redirect')));
}
catch(Exceptions\UserNotFoundException | Exceptions\SessionLoginCodeNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
