<?
/**
 * POST		/ebooks-placeholders
 */

use function Safe\session_start;

/** @var string $urlPath Passed from script this is included from. */
$ebook = null;

try{
	session_start();

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditEbookPlaceholders){
		throw new Exceptions\PermissionsInvalidException();
	}

	$ebook = new Ebook();

	$ebook->FillFromEbookPlaceholderForm();

	// Do we have a `Project` to create at the same time?
	$project = null;
	if($ebook->EbookPlaceholder?->IsInProgress){
		$project = new Project();
		$project->FillFromRequestBody();
		$project->Started = NOW;
		$project->Validate(true, true);
	}

	try{
		$ebook->Create();
	}
	catch(Exceptions\EbookExistsException $ex){
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
	$_SESSION['ebook-placeholder/create/is-created'] = true;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: /ebook-placeholders/new');
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\PermissionsInvalidException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
catch(Exceptions\EbookInvalidException | Exceptions\EbookPlaceholderExistsException | Exceptions\ProjectInvalidException $ex){
	$_SESSION['ebook'] = $ebook;
	$_SESSION['ebook-placeholder/create/exception'] = $ex;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: /ebook-placeholders/new');
}
