<?
use function Safe\session_start;

try{
	session_start();

	$project = Project::Get(HttpInput::Int(GET, 'project-id'));

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditProjects){
		throw new Exceptions\InvalidPermissionsException();
	}

	$exceptionRedirectUrl = $project->EditUrl;

	$project->FillFromHttpPost();

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
