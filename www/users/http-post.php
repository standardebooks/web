<?
/**
 * POST		/users
 */

use function Safe\session_start;

try{
	session_start();

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanCreateUsers){
		throw new Exceptions\PermissionsInvalidException();
	}

	$passwordAction = Enums\PasswordActionType::tryFrom(Http::$Request->Body->Get('password-action') ?? '') ?? Enums\PasswordActionType::None;

	$user = new User();

	$user->FillFromRequestBody();
	$user->Benefits->FillFromRequestBody();

	$user->Create(Http::$Request->Body->Get('user-password'));

	$_SESSION['is-user-created'] = true;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: ' . $user->Url);

}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\PermissionsInvalidException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
catch(Exceptions\UserInvalidException | Exceptions\UserExistsException $ex){
	if(isset($generateNewUuid) && $generateNewUuid){
		if(isset($oldUuid)){
			$user->Uuid = $oldUuid;
		}
		$_SESSION['generate-new-uuid'] = $generateNewUuid;
	}

	$_SESSION['password-action'] = $passwordAction;

	if($ex instanceof Exceptions\UserInvalidException && $ex->Has(Exceptions\BenefitsRequirePasswordException::class) && isset($oldPasswordHash)){
		$user->PasswordHash = $oldPasswordHash;
	}

	$_SESSION['user'] = $user;
	$_SESSION['user/create/exception'] = $ex;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: /users/new');
}
