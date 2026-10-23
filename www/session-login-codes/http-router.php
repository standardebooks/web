<?
/**
 * PATCH	/users/:user-identifier/session-login-codes/:session-login-code
 */

try{
	$user = User::GetByIdentifier(Http::$Request->QueryString->Get('user-identifier'));

	// Email links use `_method=PATCH` to invoke `PATCH` from a browser `GET` request.
	Http::$Request->CalculateRequestMethod(true);
	Http::$Request->Route(allowedHttpMethods: [Enums\HttpMethod::Patch], resource: $user);
}
catch(Exceptions\UserNotFoundException | Exceptions\AmbiguousUserException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
