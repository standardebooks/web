<?
use function Safe\session_start;

try{
	session_start();
	$artwork = Artwork::GetByUrl(HttpInput::Str(GET, 'artist-url-name'), HttpInput::Str(GET, 'artwork-url-name'));

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	$exceptionRedirectUrl = $artwork->Url;

	// We can PATCH the status, the ebook www filesystem path, or both.
	if(isset($_POST['artwork-status'])){
		$newStatus = Enums\ArtworkStatusType::tryFrom(HttpInput::Str(POST, 'artwork-status') ?? '');
		if($artwork->Status != $newStatus){
			if(!$artwork->CanStatusBeChangedBy(Session::$User)){
				throw new Exceptions\InvalidPermissionsException();
			}

			if($newStatus !== null){
				$artwork->ReviewerUserId = Session::$User->UserId;
				$artwork->Status = $newStatus;
			}
			else{
				unset($artwork->Status);
			}
		}
	}

	if(isset($_POST['artwork-ebook-url'])){
		$newEbookUrl = HttpInput::Str(POST, 'artwork-ebook-url');
		if(isset($newEbookUrl)){
			try{
				$newEbook = Ebook::GetByIdentifier($newEbookUrl);
			}
			catch(Exceptions\EbookNotFoundException){
				throw new Exceptions\InvalidUrlException($newEbookUrl);
			}

			if($artwork->EbookId != $newEbook->EbookId && !$artwork->CanEbookUrlBeChangedBy(Session::$User)){
				throw new Exceptions\InvalidPermissionsException();
			}

			$artwork->EbookId = $newEbook->EbookId;
		}
		else{
			if(isset($artwork->EbookId) && !$artwork->CanEbookUrlBeChangedBy(Session::$User)){
				throw new Exceptions\InvalidPermissionsException();
			}

			$artwork->EbookId = null;
		}
	}

	$artwork->Save();

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
catch(Exceptions\InvalidArtworkException | Exceptions\InvalidArtworkTagException | Exceptions\InvalidArtistException | Exceptions\InvalidImageUploadException | Exceptions\InvalidUrlException $ex){
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
