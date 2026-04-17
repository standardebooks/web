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

	$ebook = Ebook::GetByIdentifier($urlPath);

	$_SESSION['ebook-title'] = $ebook->Title;
	$_SESSION['ebook-authors'] = $ebook->AuthorsString;

	$ebook->Delete();

	$_SESSION['is-ebook-placeholder-deleted'] = true;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('Location: /ebook-placeholders/new');
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
