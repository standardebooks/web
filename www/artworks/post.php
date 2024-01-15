<?
try{
	session_start();
	$httpMethod =HttpInput::RequestMethod();

	if($httpMethod != HTTP_POST && $httpMethod != HTTP_PATCH){
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

		$artwork = new Artwork();

		$artwork->Artist = new Artist();
		$artwork->Artist->Name = HttpInput::Str(POST, 'artist-name', false);
		$artwork->Artist->DeathYear = HttpInput::Int(POST, 'artist-year-of-death');

		$artwork->Name = HttpInput::Str(POST, 'artwork-name', false);
		$artwork->CompletedYear = HttpInput::Int(POST, 'artwork-year');
		$artwork->CompletedYearIsCirca = HttpInput::Bool(POST, 'artwork-year-is-circa', false) ?? false;
		$artwork->Tags = HttpInput::Str(POST, 'artwork-tags', false) ?? [];
		$artwork->Status = HttpInput::Str(POST, 'artwork-status', false) ?? ArtworkStatus::Unverified;
		$artwork->EbookWwwFilesystemPath = HttpInput::Str(POST, 'artwork-ebook-www-filesystem-path', false);
		$artwork->SubmitterUserId = $GLOBALS['User']->UserId ?? null;
		$artwork->IsPublishedInUs = HttpInput::Bool(POST, 'artwork-is-published-in-us', false);
		$artwork->PublicationYear = HttpInput::Int(POST, 'artwork-publication-year');
		$artwork->PublicationYearPageUrl = HttpInput::Str(POST, 'artwork-publication-year-page-url', false);
		$artwork->CopyrightPageUrl = HttpInput::Str(POST, 'artwork-copyright-page-url', false);
		$artwork->ArtworkPageUrl = HttpInput::Str(POST, 'artwork-artwork-page-url', false);
		$artwork->MuseumUrl = HttpInput::Str(POST, 'artwork-museum-url', false);
		$artwork->Exception = HttpInput::Str(POST, 'artwork-exception', false);
		$artwork->Notes = HttpInput::Str(POST, 'artwork-notes', false);

		// Only approved reviewers can set the status to anything but unverified when uploading
		// The submitter cannot review their own submissions unless they have special permission
		if($artwork->Status !== ArtworkStatus::Unverified && !$GLOBALS['User']->Benefits->CanReviewOwnArtwork){
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

	// PATCHing a new artwork
	if($httpMethod == HTTP_PATCH){
		if(!$GLOBALS['User']->Benefits->CanReviewArtwork){
			throw new Exceptions\InvalidPermissionsException();
		}

		$artwork = Artwork::GetByUrl(HttpInput::Str(GET, 'artist-url-name', false), HttpInput::Str(GET, 'artwork-url-name', false));

		$artwork->Artist->Name = HttpInput::Str(POST, 'artist-name', false) ?? $artwork->Artist->Name;
		$artwork->Artist->DeathYear = HttpInput::Int(POST, 'artist-year-of-death') ?? $artwork->Artist->DeathYear;

		$artwork->Name = HttpInput::Str(POST, 'artwork-name', false) ?? $artwork->Name;
		$artwork->CompletedYear = HttpInput::Int(POST, 'artwork-year') ?? $artwork->CompletedYear;
		$artwork->CompletedYearIsCirca = HttpInput::Bool(POST, 'artwork-year-is-circa', false) ?? $artwork->CompletedYearIsCirca;
		$artwork->Tags = HttpInput::Str(POST, 'artwork-tags', false) ?? $artwork->Tags;
		$artwork->EbookWwwFilesystemPath = HttpInput::Str(POST, 'artwork-ebook-www-filesystem-path', false) ?? $artwork->EbookWwwFilesystemPath;
		$artwork->IsPublishedInUs = HttpInput::Bool(POST, 'artwork-is-published-in-us', false) ?? $artwork->IsPublishedInUs;
		$artwork->PublicationYear = HttpInput::Int(POST, 'artwork-publication-year') ?? $artwork->PublicationYear;
		$artwork->PublicationYearPageUrl = HttpInput::Str(POST, 'artwork-publication-year-page-url', false) ?? $artwork->PublicationYearPageUrl;
		$artwork->CopyrightPageUrl = HttpInput::Str(POST, 'artwork-copyright-page-url', false) ?? $artwork->CopyrightPageUrl;
		$artwork->ArtworkPageUrl = HttpInput::Str(POST, 'artwork-artwork-page-url', false) ?? $artwork->ArtworkPageUrl;
		$artwork->MuseumUrl = HttpInput::Str(POST, 'artwork-museum-url', false) ?? $artwork->MuseumUrl;
		$artwork->Exception = HttpInput::Str(POST, 'artwork-exception', false) ?? $artwork->Exception;
		$artwork->Notes = HttpInput::Str(POST, 'artwork-notes', false) ?? $artwork->Notes;

		$artwork->ReviewerUserId = $GLOBALS['User']->UserId;

		$newStatus = ArtworkStatus::tryFrom(HttpInput::Str(POST, 'artwork-status', false) ?? '');
		if($newStatus !== null){
			if($artwork->Status != $newStatus){
				// Is the user attempting to review their own artwork?
				if($artwork->Status != ArtworkStatus::Unverified && $GLOBALS['User']->UserId == $artwork->SubmitterUserId && !$GLOBALS['User']->Benefits->CanReviewOwnArtwork){
					throw new Exceptions\InvalidPermissionsException();
				}
			}

			$artwork->Status = $newStatus;
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
	$artwork = $artwork ?? null;

	$_SESSION['artwork'] = $artwork;
	$_SESSION['exception'] = $exception;

	http_response_code(303);

	if($httpMethod == HTTP_PATCH && $artwork !== null){
		header('Location: ' . $artwork->Url);
	}
	else{
		header('Location: /artworks/new');
	}

}
