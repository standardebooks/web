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

		// Confirm that we have an image and that it came from POST
		$imagePath = null;
		if(isset($_FILES['artwork-image']) && $_FILES['artwork-image']['size'] > 0){
			if(!is_uploaded_file($_FILES['artwork-image']['tmp_name']) || $_FILES['artwork-image']['error'] > UPLOAD_ERR_OK){
				throw new Exceptions\InvalidImageUploadException();
			}

			$imagePath = $_FILES['artwork-image']['tmp_name'] ?? null;
		}

		$artwork->Create($imagePath);

		$_SESSION['artwork'] = $artwork;
		$_SESSION['artwork-created'] = true;

		http_response_code(303);
		header('Location: /artworks/new');
	}

	// PUTing an artwork
	if($httpMethod == HTTP_PUT){
		$originalArtwork = Artwork::GetByUrl(HttpInput::Str(GET, 'artist-url-name'), HttpInput::Str(GET, 'artwork-url-name'));

		if(!$originalArtwork->CanBeEditedBy($GLOBALS['User'])){
			throw new Exceptions\InvalidPermissionsException();
		}

		$exceptionRedirectUrl = $originalArtwork->EditUrl;

		$artwork = Artwork::FromHttpPost();
		$artwork->ArtworkId = $originalArtwork->ArtworkId;
		$artwork->Created = $originalArtwork->Created;
		$artwork->SubmitterUserId = $originalArtwork->SubmitterUserId;
		$artwork->Status = $originalArtwork->Status; // Overwrite any value got from POST because we need permission to change the status

		$newStatus = ArtworkStatus::tryFrom(HttpInput::Str(POST, 'artwork-status') ?? '');
		if($newStatus !== null){
			if($originalArtwork->Status != $newStatus && !$originalArtwork->CanStatusBeChangedBy($GLOBALS['User'])){
				throw new Exceptions\InvalidPermissionsException();
			}

			$artwork->ReviewerUserId = $GLOBALS['User']->UserId;
			$artwork->Status = $newStatus;
		}

		// Confirm that we have an image and that it came from POST
		$imagePath = null;
		if(isset($_FILES['artwork-image']) && $_FILES['artwork-image']['size'] > 0){
			if(!is_uploaded_file($_FILES['artwork-image']['tmp_name']) || $_FILES['artwork-image']['error'] > UPLOAD_ERR_OK){
				throw new Exceptions\InvalidImageUploadException();
			}

			$imagePath = $_FILES['artwork-image']['tmp_name'] ?? null;
		}
		else{
			// No uploaded file as part of this edit, so retain the MimeType of the original submission.
			$artwork->MimeType = $originalArtwork->MimeType;
		}

		$artwork->Save($imagePath);

		$_SESSION['artwork'] = $artwork;
		$_SESSION['artwork-saved'] = true;

		http_response_code(303);
		header('Location: ' . $artwork->Url);
	}

	// PATCHing a new artwork
	if($httpMethod == HTTP_PATCH){
		$artwork = Artwork::GetByUrl(HttpInput::Str(GET, 'artist-url-name'), HttpInput::Str(GET, 'artwork-url-name'));

		$exceptionRedirectUrl = $artwork->Url;

		// We can PATCH the status, the ebook www filesystem path, or both.
		if(isset($_POST['artwork-status'])){
			$newStatus = ArtworkStatus::tryFrom(HttpInput::Str(POST, 'artwork-status') ?? '');
			if($newStatus !== null){
				if($artwork->Status != $newStatus && !$artwork->CanStatusBeChangedBy($GLOBALS['User'])){
					throw new Exceptions\InvalidPermissionsException();
				}

				$artwork->ReviewerUserId = $GLOBALS['User']->UserId;
			}

			$artwork->Status = $newStatus;
		}

		if(isset($_POST['artwork-ebook-url'])){
			$newEbookUrl = HttpInput::Str(POST, 'artwork-ebook-url');
			if($artwork->EbookUrl != $newEbookUrl && !$artwork->CanEbookUrlBeChangedBy($GLOBALS['User'])){
				throw new Exceptions\InvalidPermissionsException();
			}

			$artwork->EbookUrl = $newEbookUrl;
		}

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
	$_SESSION['artwork'] = $artwork;
	$_SESSION['exception'] = $exception;

	http_response_code(303);
	header('Location: ' . $exceptionRedirectUrl);
}
