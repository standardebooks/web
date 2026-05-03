<?
/**
 * DELETE		/ebooks/:url-path
 */

use function Safe\session_start;

try{
	session_start();

	/** @var Ebook $ebook The `Ebook` for this request, passed in from the router. */
	$ebook = $resource ?? throw new Exceptions\EbookNotFoundException();

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

	$_SESSION['ebook-title'] = $ebook->Title;
	$_SESSION['ebook-authors'] = $ebook->AuthorsString;

	$ebook->Delete();

	$_SESSION['is-ebook-placeholder-deleted'] = true;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: /ebook-placeholders/new');
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
