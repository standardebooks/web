<?

/** @var string $identifier Passed from script this is included from. */
$ebook = null;

try{
	session_start();
	$httpMethod = HttpInput::ValidateRequestMethod([Enums\HttpMethod::Post, Enums\HttpMethod::Put, Enums\HttpMethod::Delete]);
	$exceptionRedirectUrl = '/ebook-placeholders/new';

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditEbookPlaceholders){
		throw new Exceptions\InvalidPermissionsException();
	}

	// POSTing an `EbookPlaceholder`.
	if($httpMethod == Enums\HttpMethod::Post){
		$ebook = new Ebook();

		$ebook->FillFromEbookPlaceholderForm();

		// Do we have a `Project` to create at the same time?
		$project = null;
		if($ebook->EbookPlaceholder?->IsInProgress){
			$project = new Project();
			$project->FillFromHttpPost();
			$project->Started = NOW;
			$project->Validate(true, true);
		}

		try{
			$ebook->Create();
		}
		catch(Exceptions\DuplicateEbookException $ex){
			$ebook = Ebook::GetByIdentifier($ebook->Identifier);

			// An existing `EbookPlaceholder` already exists.
			$ex = new Exceptions\EbookPlaceholderExistsException('An ebook placeholder already exists for this book: <a href="' . $ebook->Url . '">' . Formatter::EscapeHtml($ebook->Title) . '</a>.');

			$ex->MessageType = Enums\ExceptionMessageType::Html;

			throw $ex;
		}

		if($ebook->EbookPlaceholder?->IsInProgress && $project !== null){
			$project->EbookId = $ebook->EbookId;
			$project->Ebook = $ebook;
			$project->Create();
		}

		$_SESSION['ebook'] = $ebook;
		$_SESSION['is-ebook-placeholder-created'] = true;

		http_response_code(Enums\HttpCode::SeeOther->value);
		header('Location: /ebook-placeholders/new');
	}

	// PUT an `EbookPlaceholder`.
	if($httpMethod == Enums\HttpMethod::Put){
		$originalEbook = Ebook::GetByIdentifier($identifier);
		$exceptionRedirectUrl = $originalEbook->EditUrl;

		$ebook = new Ebook();

		$ebook->FillFromEbookPlaceholderForm();
		$ebook->EbookId = $originalEbook->EbookId;
		$ebook->Created = $originalEbook->Created;

		$ebook->Save();

		$_SESSION['is-ebook-placeholder-saved'] = true;
		http_response_code(Enums\HttpCode::SeeOther->value);
		header('Location: ' . $ebook->Url);
	}

	// DELETE an `EbookPlaceholder`.
	if($httpMethod == Enums\HttpMethod::Delete){
		$ebook = Ebook::GetByIdentifier($identifier);

		$ebook->Delete();

		$_SESSION['ebook'] = $ebook;
		$_SESSION['is-ebook-placeholder-deleted'] = true;

		http_response_code(Enums\HttpCode::SeeOther->value);
		header('Location: /ebook-placeholders/new');
	}
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException | Exceptions\InvalidHttpMethodException | Exceptions\HttpMethodNotAllowedException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
catch(Exceptions\InvalidEbookException | Exceptions\EbookPlaceholderExistsException | Exceptions\InvalidProjectException $ex){
	$_SESSION['ebook'] = $ebook;
	$_SESSION['exception'] = $ex;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('Location: ' . $exceptionRedirectUrl);
}
