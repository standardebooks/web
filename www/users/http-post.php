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

	$user = new User();

	$user->FillFromRequestBody();
	$user->Benefits->FillFromRequestBody();

	$user->Create();

	$_SESSION['user/create/is-created'] = true;

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
		$_SESSION['user/create/generate-new-uuid'] = $generateNewUuid;
	}

	$_SESSION['user/create/user'] = $user;
	$_SESSION['user/create/exception'] = $ex;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: /users/new');
}
