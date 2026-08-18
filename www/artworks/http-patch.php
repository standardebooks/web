<?
/**
 * PATCH		/artworks/:artist-url-name/:artwork-url-name
 */

use function Safe\session_start;

$stagedImagePath = null;

try{
	session_start();

	/** @var Artwork $artwork The `Artwork` for this request, passed in from the router. */
	$artwork = $resource ?? throw new Exceptions\ArtworkNotFoundException();

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	// We may have been called from either the `Artwork`'s page, or from the `Artwork`'s edit form, so check the referrer to see which one it was.
	$referrer = Http::$Request->Headers['referer'] ?? $artwork->EditUrl;
	$exceptionRedirectUrl = Template::SanitizeRedirectUrl($referrer);

	$artworkStatus = Http::$Request->Body->Get('artwork-status');
	$artworkEbookUrl = Http::$Request->Body->Get('artwork-ebook-url');
	$isEditFormSubmission = isset(Http::$Request->Body->Variables['artwork-name']);

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
		throw new Exceptions\PermissionsInvalidException();
	}

	if($isEditFormSubmission){
		$stagedImageToken = Http::$Request->Body->Get('artwork-image-token');
		$imageTokenSecret = Http::$Request->Session->Get('artwork/edit/image-token-secret');
		$stagedImagePath = ImageTempDirectory::GetStagedImagePath($stagedImageToken, $imageTokenSecret);
		$uploadedImagePath = Http::$Request->Files->Get('artwork-image');

		unset($_SESSION['artwork/edit/image-token-secret']);

		$stagedImagePath = ImageTempDirectory::ReplaceImage($stagedImagePath, $uploadedImagePath);

		if($stagedImagePath !== null){
			$_SESSION['artwork/edit/image-path'] = $stagedImagePath;
		}
	}

	try{
		$originalArtworkStatus = $artwork->Status;

		$artwork->FillFromRequestBody();

		if($artworkStatus !== null && $artwork->Status != $originalArtworkStatus){
			$artwork->ReviewerUserId = Session::$User->UserId;
		}
	}
	catch(Exceptions\UrlInvalidException $ex){
		// Restore the original artwork so the user can correct the error and try again.
		$artwork = Artwork::Get($artwork->ArtworkId);
		throw $ex;
	}

	$artwork->Save($stagedImagePath);
	if($isEditFormSubmission){
		ImageTempDirectory::RemoveImage($stagedImagePath);

		unset($_SESSION['artwork/edit/image-path']);
	}

	$_SESSION['artwork/edit/artwork'] = $artwork;
	$_SESSION['artwork/edit/is-saved'] = true;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: ' . $artwork->Url);
}
catch(Exceptions\ArtworkNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\PermissionsInvalidException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
catch(Exceptions\ArtworkInvalidException | Exceptions\ArtworkTagInvalidException | Exceptions\ArtistInvalidException | Exceptions\ImageUploadInvalidException | Exceptions\FileUploadInvalidException | Exceptions\FileUploadTooLargeException | Exceptions\UrlInvalidException $ex){
	if($stagedImagePath !== null && ($ex instanceof Exceptions\ImageUploadInvalidException || ($ex instanceof Exceptions\ArtworkInvalidException && $ex->Has(Exceptions\ImageUploadInvalidException::class)))){
		ImageTempDirectory::RemoveImage($stagedImagePath);

		unset($_SESSION['artwork/edit/image-path']);
	}

	// If we were passed a more generic file upload exception from the HTTP request, swap it for a more specific exception to show to the user.
	if($ex instanceof Exceptions\FileUploadInvalidException || $ex instanceof Exceptions\FileUploadTooLargeException){
		$ex = new Exceptions\ImageUploadInvalidException();
	}

	// If the `Artwork` reports that no image is uploaded, check to see if the image upload was too large. If so, show the user a clearer error message.
	if($ex instanceof Exceptions\ArtworkInvalidException && $ex->Has(Exceptions\ImageUploadInvalidException::class) && Http::$Request->IsTooLarge){
		$ex->Remove(Exceptions\ImageUploadInvalidException::class);
		$ex->Add(new Exceptions\RequestInvalidException('File upload too large.'));
	}

	$_SESSION['artwork/edit/artwork'] = $artwork;
	$_SESSION['artwork/edit/exception'] = $ex;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: ' . $exceptionRedirectUrl);
}
