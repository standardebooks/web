<?

try{
	session_start();

	$httpMethod = HttpInput::ValidateRequestMethod([Enums\HttpMethod::Post, Enums\HttpMethod::Patch, Enums\HttpMethod::Put]);

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditProjects){
		throw new Exceptions\InvalidPermissionsException();
	}

	// POSTing a new `Project`.
	if($httpMethod == Enums\HttpMethod::Post){
		$project = new Project();
		$project->FillFromHttpPost();

		$project->Create();

		$_SESSION['project'] = $project;
		$_SESSION['is-project-created'] = true;

		http_response_code(Enums\HttpCode::SeeOther->value);
		header('Location: /projects');
	}
}
catch(Exceptions\EbookNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
catch(Exceptions\InvalidProjectException | Exceptions\ProjectExistsException | Exceptions\EbookIsNotAPlaceholderException $ex){
	$_SESSION['project'] = $project;
	$_SESSION['exception'] = $ex;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('Location: /projects/new');
}
