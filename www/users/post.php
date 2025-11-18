<?
use function Safe\session_start;

try{
	session_start();
	$httpMethod = HttpInput::ValidateRequestMethod([Enums\HttpMethod::Post, Enums\HttpMethod::Patch]);
	$exceptionRedirectUrl = '/users';

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	// POSTing a `User`.
	if($httpMethod == Enums\HttpMethod::Post){
		if(!Session::$User->Benefits->CanCreateUsers){
			throw new Exceptions\InvalidPermissionsException();
		}

		$passwordAction = Enums\PasswordActionType::tryFrom(HttpInput::Str(POST, 'password-action') ?? '') ?? Enums\PasswordActionType::None;

		$user = new User();

		$exceptionRedirectUrl = '/users/new';

		$user->FillFromHttpPost();
		$user->Benefits->FillFromHttpPost();

		$user->Create(HttpInput::Str(POST, 'user-password'));

		$_SESSION['is-user-created'] = true;

		http_response_code(Enums\HttpCode::SeeOther->value);
		header('Location: ' . $user->Url);
	}

	// PATCHing a `User`.
	if($httpMethod == Enums\HttpMethod::Patch){
		if(!Session::$User->Benefits->CanEditUsers){
			throw new Exceptions\InvalidPermissionsException();
		}

		$user = User::Get(HttpInput::Int(GET, 'user-id'));

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
	header('Location: ' . $exceptionRedirectUrl);
}
