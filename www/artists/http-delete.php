<?
use function Safe\session_start;

try{
	session_start();

	$artistToDelete = Artist::GetByUrlName(HttpInput::Str(GET, 'artist-url-name') ?? '');

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanReviewOwnArtwork){
		throw new Exceptions\InvalidPermissionsException();
	}

	$exceptionRedirectUrl = $artistToDelete->DeleteUrl;

	try{
		$canonicalArtist = Artist::GetByName(HttpInput::Str(POST, 'canonical-artist-name') ?? '');
	}
	catch(Exceptions\ArtistNotFoundException $ex){
		throw new Exceptions\CanonicalArtistNotFoundException();
	}

	$addAlternateName = HttpInput::Bool(POST, 'add-alternate-name');

	if($addAlternateName){
		$canonicalArtist->AddAlternateName($artistToDelete->Name);
	}

	$artistToDelete->ReassignArtworkTo($canonicalArtist);
	$artistToDelete->Delete();

	$_SESSION['is-artist-deleted'] = true;
	$_SESSION['deleted-artist'] = $artistToDelete;
	if($addAlternateName){
		$_SESSION['is-alternate-name-added'] = true;
	}

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('Location: ' . $canonicalArtist->Url);
}
catch(Exceptions\ArtistNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
catch(Exceptions\CanonicalArtistNotFoundException | Exceptions\ArtistHasArtworkException | Exceptions\ArtistAlternateNameExistsException $ex){
	$_SESSION['exception'] = $ex;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('Location: ' . $exceptionRedirectUrl);
}
