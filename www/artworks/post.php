<?

try{
	session_start();
	$httpMethod = HttpInput::ValidateRequestMethod([Enums\HttpMethod::Post, Enums\HttpMethod::Patch, Enums\HttpMethod::Put]);
	$exceptionRedirectUrl = '/artworks/new';

	if(HttpInput::IsRequestTooLarge()){
		throw new Exceptions\InvalidRequestException('File upload too large.');
	}

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	// POSTing a new artwork
	if($httpMethod == Enums\HttpMethod::Post){
		if(!Session::$User->Benefits->CanUploadArtwork){
			throw new Exceptions\InvalidPermissionsException();
		}

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

		$artwork->Create(HttpInput::File('artwork-image'));

		$_SESSION['artwork'] = $artwork;
		$_SESSION['is-created'] = true;

		http_response_code(303);
		header('Location: /artworks/new');
	}

	// PUTing an artwork
	if($httpMethod == Enums\HttpMethod::Put){
		$originalArtwork = Artwork::GetByUrl(HttpInput::Str(GET, 'artist-url-name'), HttpInput::Str(GET, 'artwork-url-name'));

		if(!$originalArtwork->CanBeEditedBy(Session::$User)){
			throw new Exceptions\InvalidPermissionsException();
		}

		$exceptionRedirectUrl = $originalArtwork->EditUrl;

		$artwork = Artwork::FromHttpPost();
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
		$_SESSION['is-saved'] = true;

		http_response_code(303);
		header('Location: ' . $artwork->Url);
	}

	// PATCHing an artwork
	if($httpMethod == Enums\HttpMethod::Patch){
		$artwork = Artwork::GetByUrl(HttpInput::Str(GET, 'artist-url-name'), HttpInput::Str(GET, 'artwork-url-name'));

		$exceptionRedirectUrl = $artwork->Url;

		// We can PATCH the status, the ebook www filesystem path, or both.
		if(isset($_POST['artwork-status'])){
			$newStatus = Enums\ArtworkStatusType::tryFrom(HttpInput::Str(POST, 'artwork-status') ?? '');
			if($newStatus !== null){
				if($artwork->Status != $newStatus && !$artwork->CanStatusBeChangedBy(Session::$User)){
					throw new Exceptions\InvalidPermissionsException();
				}

				$artwork->ReviewerUserId = Session::$User->UserId;

				$artwork->Status = $newStatus;
			}
			else{
				unset($artwork->Status);
			}
		}

		if(isset($_POST['artwork-ebook-url'])){
			$newEbookUrl = HttpInput::Str(POST, 'artwork-ebook-url');
			if($artwork->EbookUrl != $newEbookUrl && !$artwork->CanEbookUrlBeChangedBy(Session::$User)){
				throw new Exceptions\InvalidPermissionsException();
			}

			$artwork->EbookUrl = $newEbookUrl;
		}

		$artwork->Save();

		$_SESSION['artwork'] = $artwork;
		$_SESSION['is-saved'] = true;

		http_response_code(303);
		header('Location: ' . $artwork->Url);
	}
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
catch(Exceptions\InvalidArtworkException | Exceptions\InvalidArtworkTagException | Exceptions\InvalidArtistException | Exceptions\InvalidFileUploadException $ex){
	// If we were passed a more generic file upload exception from `HttpInput`, swap it for a more specific exception to show to the user.
	if($ex instanceof Exceptions\InvalidFileUploadException){
		$ex = new Exceptions\InvalidImageUploadException();
	}

	$_SESSION['artwork'] = $artwork;
	$_SESSION['exception'] = $ex;

	http_response_code(303);
	header('Location: ' . $exceptionRedirectUrl);
}
