<?

use Exceptions\InvalidImageUploadException;

try{
	session_start();
	$httpMethod = HttpInput::ValidateRequestMethod([HttpMethod::Post, HttpMethod::Patch, HttpMethod::Put]);
	$exceptionRedirectUrl = '/artworks/new';

	if(HttpInput::IsRequestTooLarge()){
		throw new Exceptions\InvalidRequestException('File upload too large.');
	}

	if($GLOBALS['User'] === null){
		throw new Exceptions\LoginRequiredException();
	}

	// POSTing a new artwork
	if($httpMethod == HttpMethod::Post){
		if(!$GLOBALS['User']->Benefits->CanUploadArtwork){
			throw new Exceptions\InvalidPermissionsException();
		}

		$artwork = Artwork::FromHttpPost();
		$artwork->SubmitterUserId = $GLOBALS['User']->UserId ?? null;

		// Only approved reviewers can set the status to anything but unverified when uploading.
		// The submitter cannot review their own submissions unless they have special permission.
		if($artwork->Status !== ArtworkStatusType::Unverified && !$artwork->CanStatusBeChangedBy($GLOBALS['User'])){
			throw new Exceptions\InvalidPermissionsException();
		}

		// If the artwork is approved, set the reviewer
		if($artwork->Status !== ArtworkStatusType::Unverified){
			$artwork->ReviewerUserId = $GLOBALS['User']->UserId;
		}

		$artwork->Create(HttpInput::File('artwork-image'));

		$_SESSION['artwork'] = $artwork;
		$_SESSION['is-artwork-created'] = true;

		http_response_code(303);
		header('Location: /artworks/new');
	}

	// PUTing an artwork
	if($httpMethod == HttpMethod::Put){
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

		$newStatus = ArtworkStatusType::tryFrom(HttpInput::Str(POST, 'artwork-status') ?? '');
		if($newStatus !== null){
			if($originalArtwork->Status != $newStatus && !$originalArtwork->CanStatusBeChangedBy($GLOBALS['User'])){
				throw new Exceptions\InvalidPermissionsException();
			}

			$artwork->ReviewerUserId = $GLOBALS['User']->UserId;
			$artwork->Status = $newStatus;
		}

		$artwork->Save(HttpInput::File('artwork-image'));

		$_SESSION['artwork'] = $artwork;
		$_SESSION['is-artwork-saved'] = true;

		http_response_code(303);
		header('Location: ' . $artwork->Url);
	}

	// PATCHing an artwork
	if($httpMethod == HttpMethod::Patch){
		$artwork = Artwork::GetByUrl(HttpInput::Str(GET, 'artist-url-name'), HttpInput::Str(GET, 'artwork-url-name'));

		$exceptionRedirectUrl = $artwork->Url;

		// We can PATCH the status, the ebook www filesystem path, or both.
		if(isset($_POST['artwork-status'])){
			$newStatus = ArtworkStatusType::tryFrom(HttpInput::Str(POST, 'artwork-status') ?? '');
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
		$_SESSION['is-artwork-saved'] = true;

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
catch(Exceptions\InvalidArtworkException | Exceptions\InvalidArtworkTagException | Exceptions\InvalidArtistException | Exceptions\InvalidFileUploadException | Exceptions\ArtworkNotFoundException $ex){

	// If we were passed a more generic file upload exception from `HttpInput`, swap it for a more specific exception to show to the user.
	if($ex instanceof Exceptions\InvalidFileUploadException){
		$ex = new InvalidImageUploadException();
	}

	$_SESSION['artwork'] = $artwork;
	$_SESSION['exception'] = $ex;

	http_response_code(303);
	header('Location: ' . $exceptionRedirectUrl);
}
