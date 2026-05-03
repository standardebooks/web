<?
/**
 * PATCH	/users/:user-identifier
 */
use function Safe\session_start;

try{
	session_start();

	/** @var User $user The `User` for this request, passed in from the router. */
	$user = $resource ?? throw new Exceptions\UserNotFoundException();

	$originalUser = $user;

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditUsers){
		throw new Exceptions\InvalidPermissionsException();
	}

	$user->FillFromHttpPost();

	$generateNewUuid = HttpInput::Bool(POST, 'generate-new-uuid') ?? false;

	if($generateNewUuid){
		$user->GenerateUuid();
	}

	$passwordAction = Enums\PasswordActionType::tryFrom(HttpInput::Str(POST, 'password-action') ?? '') ?? Enums\PasswordActionType::None;
	$oldPasswordHash = $user->PasswordHash;

	switch($passwordAction){
		case Enums\PasswordActionType::Delete:
			$user->PasswordHash = null;
			break;

		case Enums\PasswordActionType::Edit:
			$password = HttpInput::Str(POST, 'user-password');

			if($password !== null){
				$user->PasswordHash = password_hash($password, PASSWORD_DEFAULT);
			}

			break;
	}

	$user->Save(false);

	$_SESSION['is-user-saved'] = true;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: ' . $user->Url);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
catch(Exceptions\UserNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\InvalidUserException | Exceptions\UserExistsException $ex){
	if($generateNewUuid){
		$user->Uuid = $originalUser->Uuid;
		$_SESSION['generate-new-uuid'] = $generateNewUuid;
	}

	$_SESSION['password-action'] = $passwordAction;

	if($ex instanceof Exceptions\InvalidUserException && $ex->Has(Exceptions\BenefitsRequirePasswordException::class) && isset($oldPasswordHash)){
		$user->PasswordHash = $oldPasswordHash;
	}

	$_SESSION['user'] = $user;
	$_SESSION['exception'] = $ex;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: ' . $originalUser->EditUrl);
}
