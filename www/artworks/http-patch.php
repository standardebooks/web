<?
use function Safe\session_start;

try{
	session_start();
	$originalArtwork = Artwork::GetByUrl(HttpInput::Str(GET, 'artist-url-name'), HttpInput::Str(GET, 'artwork-url-name'));
	$artwork = $originalArtwork;

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	// We may have been called from either the `Artwork`'s page, or from the `Artwork`'s edit form, so check the referrer to see which one it was.
	/** @var string $exceptionRedirectUrl */
	$exceptionRedirectUrl = $_SERVER['HTTP_REFERER'] ?? $artwork->EditUrl;

	$artworkStatus = Enums\ArtworkStatusType::tryFrom(HttpInput::Str(POST, 'artwork-status') ?? '');
	$artworkEbookUrl = HttpInput::Str(POST, 'artwork-ebook-url', true);

	$isPatchingArtworkStatus = $artworkStatus !== null && $artwork->CanStatusBeChangedBy(Session::$User) && !$artwork->CanBeEditedBy(Session::$User);
	$isPatchingArtworkEbookUrl = $artworkEbookUrl !== null && $artwork->CanEbookUrlBeChangedBy(Session::$User) && !$artwork->CanBeEditedBy(Session::$User);

	if(
		!$isPatchingArtworkStatus
		&&
		!$isPatchingArtworkEbookUrl
		&&
		!$artwork->CanBeEditedBy(Session::$User)
	){
		throw new Exceptions\InvalidPermissionsException();
	}

	if($isPatchingArtworkStatus){
		$artwork->Status = $artworkStatus;
	}

	if($isPatchingArtworkEbookUrl){
		if($artworkEbookUrl == ''){
			$artwork->Ebook = null;
			$artwork->EbookId = null;
		}
		else{
			try{
				$ebook = Ebook::GetByIdentifier($artworkEbookUrl);
				$artwork->EbookId = $ebook->EbookId;
			}
			catch(Exceptions\EbookNotFoundException){
				$error = new Exceptions\InvalidArtworkException();
				$error->Add(new Exceptions\InvalidUrlException($artworkEbookUrl));
				throw $error;
			}
		}
	}

	if(!$isPatchingArtworkStatus && !$isPatchingArtworkEbookUrl && $artwork->CanBeEditedBy(Session::$User)){
		$artwork->FillFromHttpPost();
	}

	if($artworkStatus !== null && $artwork->Status != $originalArtwork->Status){
		$artwork->ReviewerUserId = Session::$User->UserId;
	}

	if($isPatchingArtworkStatus || $isPatchingArtworkEbookUrl){
		$artwork->Save();
	}
	else{
		$artwork->Save(HttpInput::File('artwork-image'));
	}

	$_SESSION['artwork'] = $artwork;
	$_SESSION['is-artwork-saved'] = true;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('Location: ' . $artwork->Url);
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
	header('Location: ' . $exceptionRedirectUrl);
}
