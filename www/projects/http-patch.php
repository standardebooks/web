<?
use function Safe\session_start;

try{
	session_start();

	$project = Project::Get(HttpInput::Int(GET, 'project-id'));

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	$exceptionRedirectUrl = $project->EditUrl;
	$projectStatus = Enums\ProjectStatusType::tryFrom(HttpInput::Str(POST, 'project-status') ?? '');

	// Any logged-in `User` who can edit a a `Project` can save any part of the `Project`; additionally, it's also allowed to update `Project::$Status` if the logged-in `User` is the `Project`'s manager or reviewer.
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
		throw new Exceptions\InvalidPermissionsException();
	}

	if(!Session::$User->Benefits->CanEditProjects && $projectStatus !== null){
		$project->Status = $projectStatus;
	}
	else{
		$project->FillFromHttpPost();
	}

	$project->Save();

	$_SESSION['is-project-saved'] = true;
	http_response_code(Enums\HttpCode::SeeOther->value);
	header('Location: ' . $project->Ebook->Url);
}
catch(Exceptions\ProjectNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
catch(Exceptions\InvalidProjectException $ex){
	$_SESSION['project'] = $project;
	$_SESSION['exception'] = $ex;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('Location: ' . $exceptionRedirectUrl);
}
