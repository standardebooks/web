<?
/**
 * PATCH		/ebooks/:url-path
 */

use function Safe\session_start;

try{
	session_start();

	/** @var Ebook $ebook The `Ebook` for this request, passed in from the router. */
	$ebook = $resource ?? throw new Exceptions\EbookNotFoundException();

	$originalEbook = $ebook;

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(
		!Session::$User->Benefits->CanEditEbookPlaceholders
		||
		!$ebook->IsPlaceholder()
		||
		$ebook->EbookPlaceholder === null
	){
		throw new Exceptions\InvalidPermissionsException();
	}

	$ebook->FillFromEbookPlaceholderForm();

	try{
		$ebook->Save();
	}
	catch(Exceptions\EbookExistsException){
		throw new Exceptions\EbookPlaceholderExistsException();
	}

	$_SESSION['is-ebook-placeholder-saved'] = true;
	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: ' . $ebook->Url);
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
	header('location: ' . $originalEbook->EditUrl);
}
