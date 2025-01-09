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

		// Are we creating a new placeholder at the same time?
		if(!isset($project->EbookId)){
			$project->Ebook = new Ebook();
			$project->Ebook->FillFromEbookPlaceholderForm();
			if(isset($project->Ebook->EbookPlaceholder) && ($project->Status == Enums\ProjectStatusType::InProgress || $project->Status == Enums\ProjectStatusType::Stalled)){
				$project->Ebook->EbookPlaceholder->IsInProgress = true;
			}
			$project->Ebook->Validate();

			$project->Validate(true, true);

			try{
				$project->Ebook->Create();
				$project->EbookId = $project->Ebook->EbookId;
			}
			catch(Exceptions\EbookExistsException $ex){
				// If the `Ebook` already exists, create the `Project` anyway.
				$project->Ebook = Ebook::GetByIdentifier($project->Ebook->Identifier);
				if($project->Ebook->EbookPlaceholder !== null && !$project->Ebook->EbookPlaceholder->IsInProgress){
					$project->EbookId = $project->Ebook->EbookId;
					$_SESSION['is-only-ebook-project-created'] = true;

					// Set the placeholder to in progress.
					$project->Ebook->EbookPlaceholder->IsInProgress = true;
					$project->Ebook->EbookPlaceholder->Save();
				}
				else{
					// `Ebook` exists and it's not a placeholder, so really fail.
					throw new Exceptions\EbookIsNotAPlaceholderException();
				}
			}

			$project->Create();
		}
		else{
			$project->Create();
		}

		$_SESSION['project'] = $project;
		if(!isset($_SESSION['is-only-ebook-project-created'])){
			$_SESSION['is-project-created'] = true;
		}

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
catch(Exceptions\InvalidProjectException | Exceptions\InvalidEbookException | Exceptions\ProjectExistsException | Exceptions\EbookExistsException | Exceptions\EbookIsNotAPlaceholderException $ex){
	$_SESSION['project'] = $project;
	$_SESSION['exception'] = $ex;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('Location: ' . $exceptionRedirectUrl);
}
