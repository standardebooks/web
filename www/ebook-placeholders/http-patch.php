<?
use function Safe\session_start;

/** @var string $urlPath Passed from script this is included from. */
$ebook = null;

try{
	session_start();
	$exceptionRedirectUrl = '/ebook-placeholders/new';

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditEbookPlaceholders){
		throw new Exceptions\InvalidPermissionsException();
	}

	$originalEbook = Ebook::GetByIdentifier($urlPath);
	$exceptionRedirectUrl = $originalEbook->EditUrl;

	$ebook = new Ebook();

	$ebook->FillFromEbookPlaceholderForm();
	$ebook->EbookId = $originalEbook->EbookId;
	$ebook->Created = $originalEbook->Created;
	if(isset($ebook->EbookPlaceholder) && isset($originalEbook->EbookPlaceholder)){
		$ebook->EbookPlaceholder->IsInProgress = $originalEbook->EbookPlaceholder->IsInProgress;
	}

	try{
		$ebook->Save();
	}
	catch(Exceptions\EbookExistsException){
		throw new Exceptions\EbookPlaceholderExistsException();
	}

	$_SESSION['is-ebook-placeholder-saved'] = true;
	http_response_code(Enums\HttpCode::SeeOther->value);
	header('Location: ' . $ebook->Url);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
catch(Exceptions\InvalidEbookException | Exceptions\EbookPlaceholderExistsException $ex){
	$_SESSION['ebook'] = $ebook;
	$_SESSION['exception'] = $ex;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('Location: ' . $exceptionRedirectUrl);
}
