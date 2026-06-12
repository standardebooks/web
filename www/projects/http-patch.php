<?
/**
 * PATCH	/projects/:project-id
 */

use function Safe\session_start;

try{
	session_start();

	/** @var Project $project The `Project` for this request, passed in from the router. */
	$project = $resource ?? throw new Exceptions\ProjectNotFoundException();

	$originalEditUrl = $project->EditUrl;

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	$projectStatus = Enums\ProjectStatusType::tryFrom(Http::$Request->Body->Get('project-status') ?? '');

	// Any logged-in `User` who can edit a `Project` can save any part of the `Project`; additionally, it's also allowed to update `Project::$Status` if the logged-in `User` is the `Project`'s manager or reviewer.
	if(
		!(
			(
				$projectStatus !== null
				&&
				(
					$project->ManagerUserId == Session::$User->UserId
					||
					$project->ReviewerUserId == Session::$User->UserId
				)
			)
			||
			Session::$User->Benefits->CanEditProjects
		)
	){
		throw new Exceptions\PermissionsInvalidException();
	}

	if(!Session::$User->Benefits->CanEditProjects){
		$project->Status = $projectStatus;
	}
	else{
		$project->FillFromRequestBody();
	}

	$project->Save();

	$_SESSION['project/edit/is-saved'] = true;
	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: ' . $project->Ebook->Url);
}
catch(Exceptions\ProjectNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\PermissionsInvalidException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
catch(Exceptions\ProjectInvalidException $ex){
	$_SESSION['project'] = $project;
	$_SESSION['project/edit/exception'] = $ex;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: ' . $originalEditUrl);
}
