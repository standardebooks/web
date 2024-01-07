<?
try{
	session_start();

	if(HttpInput::RequestMethod() != HTTP_POST){
		throw new Exceptions\InvalidRequestException('Only HTTP POST accepted.');
	}

	if(HttpInput::IsRequestTooLarge()){
		throw new Exceptions\InvalidRequestException('File upload too large.');
	}

	if($GLOBALS['User'] === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!$GLOBALS['User']->Benefits->CanUploadArtwork){
		throw new Exceptions\InvalidPermissionsException();
	}

	$artwork = new Artwork();

	$artwork->Artist = new Artist();
	$artwork->Artist->Name = HttpInput::Str(POST, 'artist-name', false);
	$artwork->Artist->DeathYear = HttpInput::Int(POST, 'artist-year-of-death');

	$artwork->Name = HttpInput::Str(POST, 'artwork-name', false);
	$artwork->CompletedYear = HttpInput::Int(POST, 'artwork-year');
	$artwork->CompletedYearIsCirca = HttpInput::Bool(POST, 'artwork-year-is-circa', false);
	$artwork->Tags = Artwork::ParseTags(HttpInput::Str(POST, 'artwork-tags', false));
	$artwork->Status = HttpInput::Str(POST, 'artwork-status', false, COVER_ARTWORK_STATUS_UNVERIFIED);
	$artwork->EbookWwwFilesystemPath = HttpInput::Str(POST, 'artwork-ebook-www-filesystem-path', false);
	$artwork->SubmitterUserId = $GLOBALS['User']->UserId ?? null;
	$artwork->IsPublishedInUs = HttpInput::Bool(POST, 'artwork-is-published-in-us', false);
	$artwork->PublicationYear = HttpInput::Int(POST, 'artwork-publication-year');
	$artwork->PublicationYearPageUrl = HttpInput::Str(POST, 'artwork-publication-year-page-url', false);
	$artwork->CopyrightPageUrl = HttpInput::Str(POST, 'artwork-copyright-page-url', false);
	$artwork->ArtworkPageUrl = HttpInput::Str(POST, 'artwork-artwork-page-url', false);
	$artwork->MuseumUrl = HttpInput::Str(POST, 'artwork-museum-url', false);
	$artwork->MimeType = ImageMimeType::FromFile($_FILES['artwork-image']['tmp_name'] ?? null);
	$artwork->Exception = HttpInput::Str(POST, 'artwork-exception', false);

	// Only approved reviewers can set the status to anything but unverified when uploading
	if($artwork->Status != COVER_ARTWORK_STATUS_UNVERIFIED && !$GLOBALS['User']->Benefits->CanReviewArtwork){
		throw new Exceptions\InvalidPermissionsException();
	}

	// If the artwork is approved, set the reviewer
	if($artwork->Status != COVER_ARTWORK_STATUS_UNVERIFIED){
		$artwork->ReviewerUserId = $GLOBALS['User']->UserId;
	}

	$artwork->Create($_FILES['artwork-image'] ?? []);

	$_SESSION['artwork'] = $artwork;
	$_SESSION['artwork-created'] = true;

	http_response_code(303);
	header('Location: /artworks/new');
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::Emit403();
}
catch(Exceptions\AppException $exception){
	$_SESSION['artwork'] = $artwork ?? null;
	$_SESSION['exception'] = $exception;

	http_response_code(303);
	header('Location: /artworks/new');
}
