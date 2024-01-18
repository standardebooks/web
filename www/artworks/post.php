<?
try{
	session_start();
	$httpMethod =HttpInput::RequestMethod();
	$exceptionRedirectUrl = '/artworks/new';

	if($httpMethod != HTTP_POST && $httpMethod != HTTP_PATCH && $httpMethod != HTTP_PUT){
		throw new Exceptions\InvalidRequestException();
	}

	if(HttpInput::IsRequestTooLarge()){
		throw new Exceptions\InvalidRequestException('File upload too large.');
	}

	if($GLOBALS['User'] === null){
		throw new Exceptions\LoginRequiredException();
	}

	// POSTing a new artwork
	if($httpMethod == HTTP_POST){
		if(!$GLOBALS['User']->Benefits->CanUploadArtwork){
			throw new Exceptions\InvalidPermissionsException();
		}

		$artwork = Artwork::FromHttpPost();
		$artwork->SubmitterUserId = $GLOBALS['User']->UserId ?? null;

		// Only approved reviewers can set the status to anything but unverified when uploading.
		// The submitter cannot review their own submissions unless they have special permission.
		if($artwork->Status !== ArtworkStatus::Unverified && !$artwork->CanStatusBeChangedBy($GLOBALS['User'])){
			throw new Exceptions\InvalidPermissionsException();
		}

		// If the artwork is approved, set the reviewer
		if($artwork->Status !== ArtworkStatus::Unverified){
			$artwork->ReviewerUserId = $GLOBALS['User']->UserId;
		}

		// Confirm that the files came from POST
		if(!is_uploaded_file($_FILES['artwork-image']['tmp_name'])){
			throw new Exceptions\InvalidImageUploadException();
		}

		$artwork->Create($_FILES['artwork-image'] ?? []);

		$_SESSION['artwork'] = $artwork;
		$_SESSION['artwork-created'] = true;

		http_response_code(303);
		header('Location: /artworks/new');
	}

	// PUTing an artwork
	if($httpMethod == HTTP_PUT){
		$originalArtwork = Artwork::GetByUrl(HttpInput::Str(GET, 'artist-url-name', false), HttpInput::Str(GET, 'artwork-url-name', false));

		if(!$originalArtwork->CanBeEditedBy($GLOBALS['User'])){
			throw new Exceptions\InvalidPermissionsException();
		}

		$exceptionRedirectUrl = $originalArtwork->EditUrl;

		$artwork = Artwork::FromHttpPost();
		$artwork->ArtworkId = $originalArtwork->ArtworkId;
		$artwork->Created = $originalArtwork->Created;
		$artwork->SubmitterUserId = $originalArtwork->SubmitterUserId;

		$newStatus = ArtworkStatus::tryFrom(HttpInput::Str(POST, 'artwork-status', false) ?? '');
		if($newStatus !== null){
			if($originalArtwork->Status != $newStatus && !$originalArtwork->CanStatusBeChangedBy($GLOBALS['User'])){
				throw new Exceptions\InvalidPermissionsException();
			}

			$artwork->ReviewerUserId = $GLOBALS['User']->UserId;
			$artwork->Status = $newStatus;
		}

		$uploadedFile = [];
		$uploadError = $_FILES['artwork-image']['error'];

		if($uploadError == UPLOAD_ERR_OK){
			$uploadedFile = $_FILES['artwork-image'];
		}
		// No uploaded file as part of this edit, so retain the MimeType of the original submission.
		else{
			$artwork->MimeType = $originalArtwork->MimeType;
		}

		$artwork->Save($uploadedFile);

		$_SESSION['artwork'] = $artwork;
		$_SESSION['artwork-saved'] = true;

		http_response_code(303);
		header('Location: ' . $artwork->Url);
	}

	// PATCHing a new artwork
	if($httpMethod == HTTP_PATCH){
		$artwork = Artwork::GetByUrl(HttpInput::Str(GET, 'artist-url-name', false), HttpInput::Str(GET, 'artwork-url-name', false));

		$exceptionRedirectUrl = $artwork->Url;

		// We can PATCH the status, the ebook www filesystem path, or both.

		$newStatus = ArtworkStatus::tryFrom(HttpInput::Str(POST, 'artwork-status', false) ?? '');
		if($newStatus !== null){
			if($artwork->Status != $newStatus && !$artwork->CanStatusBeChangedBy($GLOBALS['User'])){
				throw new Exceptions\InvalidPermissionsException();
			}

			$artwork->ReviewerUserId = $GLOBALS['User']->UserId;
			$artwork->Status = $newStatus;
		}

		$newEbookWwwFilesystemPath = HttpInput::Str(POST, 'artwork-ebook-www-filesystem-path', false) ?? null;
		if($artwork->EbookWwwFilesystemPath != $newEbookWwwFilesystemPath && !$artwork->CanEbookWwwFilesysemPathBeChangedBy($GLOBALS['User'])){
			throw new Exceptions\InvalidPermissionsException();
		}

		$artwork->ReviewerUserId = $GLOBALS['User']->UserId;
		$artwork->Status = $newStatus;
		$artwork->EbookWwwFilesystemPath = $newEbookWwwFilesystemPath;

		$artwork->Save();

		$_SESSION['artwork'] = $artwork;
		$_SESSION['artwork-saved'] = true;

		http_response_code(303);
		header('Location: ' . $artwork->Url);
	}
}
catch(Exceptions\InvalidRequestException){
	http_response_code(405);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::Emit403();
}
catch(Exceptions\ArtworkNotFoundException){
	Template::Emit404();
}
catch(Exceptions\AppException $exception){
	$artwork = $artwork ?? null;

	$_SESSION['artwork'] = $artwork;
	$_SESSION['exception'] = $exception;

	http_response_code(303);
	header('Location: ' . $exceptionRedirectUrl);
}
