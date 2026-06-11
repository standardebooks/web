<?
/**
 * POST		/artworks
 */

use function Safe\session_start;

try{
	session_start();

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanUploadArtwork){
		throw new Exceptions\PermissionsInvalidException();
	}

	$artwork = new Artwork();

	$artwork->FillFromRequestBody();
	$artwork->SubmitterUserId = Session::$User->UserId ?? null;

	// Only approved reviewers can set the status to anything but unverified when uploading.
	// The submitter cannot review their own submissions unless they have special permission.
	if($artwork->Status !== Enums\ArtworkStatusType::Unverified && !$artwork->CanStatusBeChangedBy(Session::$User)){
		throw new Exceptions\PermissionsInvalidException();
	}

	// If the artwork is approved, set the reviewer.
	if($artwork->Status !== Enums\ArtworkStatusType::Unverified){
		$artwork->ReviewerUserId = Session::$User->UserId;
	}

	// New uploads can be auto-approved, but not edits because the auto-approve could conflict with the edit.
	$artwork->ApproveByMuseumUrl();

	$artwork->Create(Http::$Request->Files->Get('artwork-image'));

	$_SESSION['artwork'] = $artwork;
	$_SESSION['artwork/create/is-created'] = true;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: /artworks/new');
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\PermissionsInvalidException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
catch(Exceptions\ArtworkInvalidException | Exceptions\ArtworkTagInvalidException | Exceptions\ArtistInvalidException | Exceptions\ImageUploadInvalidException | Exceptions\FileUploadInvalidException | Exceptions\FileUploadTooLargeException | Exceptions\UrlInvalidException | Exceptions\ArtworkExistsException $ex){
	// If we were passed a more generic file upload exception from the HTTP request, swap it for a more specific exception to show to the user.
	if($ex instanceof Exceptions\FileUploadInvalidException || $ex instanceof Exceptions\FileUploadTooLargeException){
		$ex = new Exceptions\ImageUploadInvalidException();
	}

	// If the `Artwork` reports that no image is uploaded, check to see if the image upload was too large. If so, show the user a clearer error message.
	if($ex instanceof Exceptions\ArtworkInvalidException && $ex->Has(Exceptions\ImageUploadInvalidException::class) && Http::$Request->IsRequestTooLarge){
		$ex->Remove(Exceptions\ImageUploadInvalidException::class);
		$ex->Add(new Exceptions\RequestInvalidException('File upload too large.'));
	}

	$_SESSION['artwork'] = $artwork;
	$_SESSION['artwork/create/exception'] = $ex;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: /artworks/new');
}
