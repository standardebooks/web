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

	$user->FillFromRequestBody();

	$generateNewUuid = Http::$Request->Body->Get('generate-new-uuid', 'bool') ?? false;

	if($generateNewUuid){
		$user->GenerateUuid();
	}

	$user->Save(false);

	$_SESSION['user/edit/is-saved'] = true;

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
		$_SESSION['user/edit/generate-new-uuid'] = $generateNewUuid;
	}

	$_SESSION['user/edit/user'] = $user;
	$_SESSION['user/edit/exception'] = $ex;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: ' . $originalEditUrl);
}
