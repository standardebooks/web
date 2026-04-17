<?
use function Safe\session_start;

try{
	session_start();

	$originalArtwork = Artwork::GetByUrl(HttpInput::Str(GET, 'artist-url-name'), HttpInput::Str(GET, 'artwork-url-name'));

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!$originalArtwork->CanBeEditedBy(Session::$User)){
		throw new Exceptions\InvalidPermissionsException();
	}

	$exceptionRedirectUrl = $originalArtwork->EditUrl;

	try{
		$artwork = new Artwork();
		$artwork->FillFromHttpPost();
	}
	catch(Exceptions\AppException $ex){
		// Restore the original artwork so the user can correct the error and try again.
		$artwork = $originalArtwork;
		throw $ex;
	}

	$artwork->ArtworkId = $originalArtwork->ArtworkId;
	$artwork->Created = $originalArtwork->Created;
	$artwork->SubmitterUserId = $originalArtwork->SubmitterUserId;
	$artwork->Status = $originalArtwork->Status; // Overwrite any value got from POST because we need permission to change the status.

	$newStatus = Enums\ArtworkStatusType::tryFrom(HttpInput::Str(POST, 'artwork-status') ?? '');
	if($newStatus !== null){
		if($originalArtwork->Status != $newStatus && !$originalArtwork->CanStatusBeChangedBy(Session::$User)){
			throw new Exceptions\InvalidPermissionsException();
		}

		$artwork->ReviewerUserId = Session::$User->UserId;
		$artwork->Status = $newStatus;
	}

	if(HttpInput::File('artwork-image') === null){
		$artwork->MimeType = $originalArtwork->MimeType;
	}

	$artwork->Save(HttpInput::File('artwork-image'));

	$_SESSION['artwork'] = $artwork;
	$_SESSION['is-artwork-saved'] = true;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('Location: ' . $artwork->Url);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
catch(Exceptions\ArtworkNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\InvalidArtworkException | Exceptions\InvalidArtworkTagException | Exceptions\InvalidArtistException | Exceptions\InvalidImageUploadException | Exceptions\InvalidFileUploadException | Exceptions\InvalidUrlException | Exceptions\InvalidRequestException $ex){
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
	header('Location: ' . $exceptionRedirectUrl);
}
