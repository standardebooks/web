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
		throw new Exceptions\InvalidPermissionsException();
	}

	$passwordAction = Enums\PasswordActionType::tryFrom(HttpInput::Str(POST, 'password-action') ?? '') ?? Enums\PasswordActionType::None;

	$user = new User();

	$user->FillFromHttpPost();
	$user->Benefits->FillFromHttpPost();

	$user->Create(HttpInput::Str(POST, 'user-password'));

	$_SESSION['is-user-created'] = true;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: ' . $user->Url);

}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
catch(Exceptions\InvalidUserException | Exceptions\UserExistsException $ex){
	if(isset($generateNewUuid) && $generateNewUuid){
		if(isset($oldUuid)){
			$user->Uuid = $oldUuid;
		}
		$_SESSION['generate-new-uuid'] = $generateNewUuid;
	}

	$_SESSION['password-action'] = $passwordAction;

	if($ex instanceof Exceptions\InvalidUserException && $ex->Has(Exceptions\BenefitsRequirePasswordException::class) && isset($oldPasswordHash)){
		$user->PasswordHash = $oldPasswordHash;
	}

	$_SESSION['user'] = $user;
	$_SESSION['exception'] = $ex;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: /users/new');
}
