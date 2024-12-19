<?

try{
	session_start();

	$httpMethod = HttpInput::ValidateRequestMethod([Enums\HttpMethod::Post, Enums\HttpMethod::Patch, Enums\HttpMethod::Put]);
	$exceptionRedirectUrl = '/projects/new';

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

	// PUTing a `Project`.
	if($httpMethod == Enums\HttpMethod::Put){
		$project = Project::Get(HttpInput::Int(GET, 'project-id'));
		$exceptionRedirectUrl = $project->EditUrl;

		$project->FillFromHttpPost();

		$project->Save();

		$_SESSION['is-project-saved'] = true;
		http_response_code(Enums\HttpCode::SeeOther->value);
		header('Location: ' . $project->Ebook->Url);
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
	header('Location: ' . $exceptionRedirectUrl);
}
