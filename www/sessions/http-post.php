<?
/**
 * POST		/sessions
 */

use function Safe\session_start;

try{
	session_start();

	$email = Http::$Request->Body->Get('email');
	$redirect = Http::$Request->Body->Get('redirect');
	$user = User::GetByEmail($email);

	if($redirect === null){
		$redirect = $user->Url;
	}
	else{
		$redirect = Template::SanitizeRedirectUrl($redirect);
	}

	// Check requests for accounts with any non-patron benefits using the existing spam detection and IP bans.
	$isSpam = $user->Benefits->HasNonPatronBenefits() && (Http::$Request->RemoteAddress === null || Http::$Request->RemoteAddress->IsBanned() !== null);

	if(!$isSpam){
		$sessionLoginCode = new SessionLoginCode($user);
		$sessionLoginCode->Create($redirect);
	}

	// Spam requests receive the same success message without creating a login code or sending an email.
	unset($_SESSION['session/create/email'], $_SESSION['session/create/redirect'], $_SESSION['session/create/exception'], $_SESSION['session/create/code-url']);

	$_SESSION['session/create/is-created'] = true;

	if(SITE_STATUS == SITE_STATUS_DEV && !$isSpam){
		$_SESSION['session/create/code-url'] = $sessionLoginCode->GetUrl($redirect);
	}

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: /sessions/new');
}
catch(Exceptions\UserNotFoundException | Exceptions\SessionLoginCodeRequestLimitException $ex){
	$_SESSION['session/create/email'] = $email;
	$_SESSION['session/create/redirect'] = $redirect;
	$_SESSION['session/create/exception'] = $ex;
	header('location: /sessions/new');
	http_response_code(Enums\HttpCode::SeeOther->value);
}
