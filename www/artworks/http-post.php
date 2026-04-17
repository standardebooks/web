<?
use function Safe\session_start;

try{
	session_start();

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanUploadArtwork){
		throw new Exceptions\InvalidPermissionsException();
	}

	$exceptionRedirectUrl = '/artworks/new';
	$artwork = new Artwork();

	$artwork->FillFromHttpPost();
	$artwork->SubmitterUserId = Session::$User->UserId ?? null;

	// Only approved reviewers can set the status to anything but unverified when uploading.
	// The submitter cannot review their own submissions unless they have special permission.
	if($artwork->Status !== Enums\ArtworkStatusType::Unverified && !$artwork->CanStatusBeChangedBy(Session::$User)){
		throw new Exceptions\InvalidPermissionsException();
	}

	// If the artwork is approved, set the reviewer.
	if($artwork->Status !== Enums\ArtworkStatusType::Unverified){
		$artwork->ReviewerUserId = Session::$User->UserId;
	}

	// New uploads can be auto-approved, but not not edits because the auto-approve could conflict with the edit.
	$artwork->ApproveByMuseumUrl();

	$artwork->Create(HttpInput::File('artwork-image'));

	$_SESSION['artwork'] = $artwork;
	$_SESSION['is-artwork-created'] = true;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('Location: /artworks/new');
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
	header('Location: ' . $exceptionRedirectUrl);
}
