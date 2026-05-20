<?
/**
 * PATCH	/users/:user-identifier
 */
use function Safe\session_start;

try{
	session_start();

	/** @var User $user The `User` for this request, passed in from the router. */
	$user = $resource ?? throw new Exceptions\UserNotFoundException();

	$originalEditUrl = $user->EditUrl;
	$originalUuid = $user->Uuid;

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditUsers){
		throw new Exceptions\PermissionsInvalidException();
	}

	$canManageProjects = $user->Benefits->CanManageProjects;
	$canReviewProjects = $user->Benefits->CanReviewProjects;
	$canBeAutoAssignedToProjects = $user->Benefits->CanBeAutoAssignedToProjects;

	$user->FillFromHttpPost();

	$deleteFromProjectUnassignedManagers = ($canManageProjects && !$user->Benefits->CanManageProjects) || ($canBeAutoAssignedToProjects && !$user->Benefits->CanBeAutoAssignedToProjects);

	$deleteFromProjectUnassignedReviewers = ($canReviewProjects && !$user->Benefits->CanReviewProjects) || ($canBeAutoAssignedToProjects && !$user->Benefits->CanBeAutoAssignedToProjects);

	$generateNewUuid = Http::$Request->Body->Get('generate-new-uuid', 'bool') ?? false;

	if($generateNewUuid){
		$user->GenerateUuid();
	}

	$passwordAction = Enums\PasswordActionType::tryFrom(Http::$Request->Body->Get('password-action') ?? '') ?? Enums\PasswordActionType::None;
	$oldPasswordHash = $user->PasswordHash;

	switch($passwordAction){
		case Enums\PasswordActionType::Delete:
			$user->PasswordHash = null;
			break;

		case Enums\PasswordActionType::Edit:
			$password = Http::$Request->Body->Get('user-password');

			if($password !== null){
				$user->PasswordHash = password_hash($password, PASSWORD_DEFAULT);
			}

			break;
	}

	$user->Save(false, $deleteFromProjectUnassignedManagers, $deleteFromProjectUnassignedReviewers);

	$_SESSION['is-user-saved'] = true;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: ' . $user->Url);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\PermissionsInvalidException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
catch(Exceptions\UserNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\UserInvalidException | Exceptions\UserExistsException $ex){
	if($generateNewUuid){
		$user->Uuid = $originalUuid;
		$_SESSION['generate-new-uuid'] = $generateNewUuid;
	}

	$_SESSION['password-action'] = $passwordAction;

	if($ex instanceof Exceptions\UserInvalidException && $ex->Has(Exceptions\BenefitsRequirePasswordException::class) && isset($oldPasswordHash)){
		$user->PasswordHash = $oldPasswordHash;
	}

	$_SESSION['user'] = $user;
	$_SESSION['exception'] = $ex;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: ' . $originalEditUrl);
}
