<?
/**
 * PATCH		/artworks/:artist-url-name/:artwork-url-name
 */

use function Safe\session_start;

try{
	session_start();

	/** @var Artwork $artwork The `Artwork` for this request, passed in from the router. */
	$artwork = $resource ?? throw new Exceptions\ArtworkNotFoundException();

	$originalArtwork = $artwork;

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	// We may have been called from either the `Artwork`'s page, or from the `Artwork`'s edit form, so check the referrer to see which one it was.
	/** @var string $referrer */
	$referrer = $_SERVER['HTTP_REFERER'] ?? $artwork->EditUrl;
	$exceptionRedirectUrl = Template::SanitizeRedirectUrl($referrer);

	$artworkStatus = HttpInput::Str(POST, 'artwork-status');
	$artworkEbookUrl = HttpInput::Str(POST, 'artwork-ebook-url');

	if(
		!(
			(
				$artworkStatus !== null
				&&
				$artwork->CanStatusBeChangedBy(Session::$User)
			)
			||
			(
				$artworkEbookUrl !== null
				&&
				$artwork->CanEbookUrlBeChangedBy(Session::$User)
			)
			||
			$artwork->CanBeEditedBy(Session::$User)
		)
	){
		throw new Exceptions\InvalidPermissionsException();
	}

	try{
		$artwork->FillFromHttpPost();

		if($artworkStatus !== null && $artwork->Status != $originalArtwork->Status){
			$artwork->ReviewerUserId = Session::$User->UserId;
		}
	}
	catch(Exceptions\InvalidUrlException $ex){
		// Restore the original artwork so the user can correct the error and try again.
		$artwork = $originalArtwork;
		throw $ex;
	}

	$artwork->Save(HttpInput::File('artwork-image'));

	$_SESSION['artwork'] = $artwork;
	$_SESSION['is-artwork-saved'] = true;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: ' . $artwork->Url);
}
catch(Exceptions\ArtworkNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
catch(Exceptions\InvalidArtworkException | Exceptions\InvalidArtworkTagException | Exceptions\InvalidArtistException | Exceptions\InvalidImageUploadException | Exceptions\InvalidFileUploadException | Exceptions\InvalidUrlException $ex){
	// If we were passed a more generic file upload exception from `HttpInput`, swap it for a more specific exception to show to the user.
	if($ex instanceof Exceptions\InvalidFileUploadException){
		$ex = new Exceptions\InvalidImageUploadException();
	}

	// If the `Artwork` reports that no image is uploaded, check to see if the image upload was too large. If so, show the user a clearer error message.
	if($ex instanceof Exceptions\InvalidArtworkException && $ex->Has(Exceptions\InvalidImageUploadException::class) && HttpInput::IsRequestTooLarge()){
		$ex->Remove(Exceptions\InvalidImageUploadException::class);
		$ex->Add(new Exceptions\InvalidRequestException('File upload too large.'));
	}

	$_SESSION['artwork'] = $artwork;
	$_SESSION['exception'] = $ex;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: ' . $exceptionRedirectUrl);
}
