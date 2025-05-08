<?
use function Safe\session_start;

try{
	session_start();
	$httpMethod = HttpInput::ValidateRequestMethod([Enums\HttpMethod::Patch]);
	$exceptionRedirectUrl = '/users';

	$user = User::Get(HttpInput::Int(GET, 'user-id'));

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditUsers){
		throw new Exceptions\InvalidPermissionsException();
	}

	// PATCHing a `User`.
	if($httpMethod == Enums\HttpMethod::Patch){
		$exceptionRedirectUrl = $user->Url . '/edit';

		$user->FillFromHttpPost();

		$generateNewUuid = HttpInput::Bool(POST, 'generate-new-uuid') ?? false;

		if($generateNewUuid){
			$oldUuid = $user->Uuid;
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

		$user->Save();

		$_SESSION['is-user-saved'] = true;

		http_response_code(Enums\HttpCode::SeeOther->value);
		header('Location: ' . $user->Url);
	}
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
		$user->Uuid = $oldUuid;
		$_SESSION['generate-new-uuid'] = $generateNewUuid;
	}

	$_SESSION['password-action'] = $passwordAction;

	if($ex instanceof Exceptions\InvalidUserException && $ex->Has(Exceptions\BenefitsRequirePasswordException::class)){
		$user->PasswordHash = $oldPasswordHash;
	}

	$_SESSION['user'] = $user;
	$_SESSION['exception'] = $ex;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('Location: ' . $exceptionRedirectUrl);
}
