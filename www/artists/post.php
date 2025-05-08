<?
use function Safe\session_start;

try{
	session_start();
	$httpMethod = HttpInput::ValidateRequestMethod([Enums\HttpMethod::Delete]);
	$exceptionRedirectUrl = '/artworks';

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanReviewOwnArtwork){
		throw new Exceptions\InvalidPermissionsException();
	}

	// DELETE an `Artist`.
	if($httpMethod == Enums\HttpMethod::Delete){
		$artistToDelete = Artist::GetByUrlName(HttpInput::Str(GET, 'artist-url-name') ?? '');
		$exceptionRedirectUrl = $artistToDelete->DeleteUrl;

		$canonicalArtist = Artist::GetByName(HttpInput::Str(POST, 'canonical-artist-name') ?? '');
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
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException | Exceptions\HttpMethodNotAllowedException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
catch(Exceptions\ArtistNotFoundException | Exceptions\ArtistHasArtworkException | Exceptions\ArtistAlternateNameExistsException $ex){
	$_SESSION['exception'] = $ex;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('Location: ' . $exceptionRedirectUrl);
}
