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
	$artworkEbookUrl = Http::$Request->Body->Get('artwork-ebook-url', 'empty-string');
	$isEditFormSubmission = isset(Http::$Request->Body->Variables['artwork-name']);
	$canEditArtwork = $artwork->CanBeEditedBy(Session::$User);
	$canChangeStatus = $artwork->CanStatusBeChangedBy(Session::$User);
	$canChangeEbookUrl = $artwork->CanEbookUrlBeChangedBy(Session::$User);

	if(
		!(
			(
				$artworkStatus !== null
				&&
				$canChangeStatus
			)
			||
			(
				$artworkEbookUrl !== null
				&&
				$canChangeEbookUrl
			)
			||
			$canEditArtwork
		)
	){
		throw new Exceptions\PermissionsInvalidException();
	}

	// Status and ebook-link permissions do not permit other edits.
	if(!$canEditArtwork){
		foreach(array_keys(Http::$Request->Body->Variables) as $field){
			if((str_starts_with($field, 'artwork-') || str_starts_with($field, 'artist-')) && !in_array($field, ['artwork-status', 'artwork-ebook-url'], true)){
				throw new Exceptions\PermissionsInvalidException();
			}
		}
	}

	// The inline ebook-link form includes the unchanged status even when the user cannot review this artwork.
	if($artworkStatus !== null && $artworkStatus !== $artwork->Status->value && !$canChangeStatus){
		throw new Exceptions\PermissionsInvalidException();
	}

	if($isEditFormSubmission){
		$stagedImageToken = Http::$Request->Body->Get('artwork-image-token');
		$imageTokenSecret = Http::$Request->Session->Get('artwork/edit/image-token-secret');
		try{
			$stagedImagePath = UploadTempDirectory::GetStagedUploadPath($stagedImageToken, $imageTokenSecret);
		}
		catch(Exceptions\FileUploadInvalidException){
			throw new Exceptions\ImageUploadInvalidException('Please re-upload the image.');
		}
		$uploadedImagePath = Http::$Request->Files->Get('artwork-image');

		unset($_SESSION['artwork/edit/image-token-secret']);

		try{
			$stagedImagePath = UploadTempDirectory::ReplaceUpload($stagedImagePath, $uploadedImagePath);
		}
		catch(Exceptions\FileUploadInvalidException){
			throw new Exceptions\ImageUploadInvalidException('Failed to save uploaded image.');
		}

		if($stagedImagePath !== null){
			$_SESSION['artwork/edit/image-path'] = $stagedImagePath;
		}
	}

	try{
		$originalArtworkStatus = $artwork->Status;
		$originalEbookId = $artwork->EbookId;

		$artwork->FillFromRequestBody();

		if($artwork->EbookId !== $originalEbookId && !$canChangeEbookUrl){
			throw new Exceptions\PermissionsInvalidException();
		}

		if($artworkStatus !== null && $artwork->Status != $originalArtworkStatus){
			$artwork->ReviewerUserId = Session::$User->UserId;
		}
	}
	catch(Exceptions\UrlInvalidException $ex){
		// Restore the original artwork so the user can correct the error and try again.
		$artwork = Artwork::Get($artwork->ArtworkId);
		throw $ex;
	}

	$submittedArtist = clone $artwork->Artist;
	Db::Transaction(function() use ($artwork, $stagedImagePath, $submittedArtist): void{
		// Use the original object in case of a deadlock retry.
		$artwork->Artist = clone $submittedArtist;

		// Resolve the same canonical artist used by the save, including alternate names, without creating one.
		$artist = Db::Query('
			select a.*
			from Artists a
			left outer join ArtistAlternateNames aan using (ArtistId)
			where a.UrlName = ? or aan.UrlName = ?
			limit 1
			for update
		', [$artwork->Artist->UrlName, $artwork->Artist->UrlName], Artist::class)[0] ?? null;

		// A blank year for a different existing artist preserves that artist's known year.
		$preserveDeathYear = $artist !== null && $artist->ArtistId !== $artwork->ArtistId && $artwork->Artist->DeathYear === null;
		if($artist !== null && !$preserveDeathYear && $artwork->Artist->DeathYear != $artist->DeathYear){
			// Shared artist changes require permission to edit every other affected artwork.
			$otherArtworks = Db::Query('select * from Artworks where ArtistId = ? and ArtworkId != ? for update', [$artist->ArtistId, $artwork->ArtworkId], Artwork::class);
			foreach($otherArtworks as $otherArtwork){
				if(!$otherArtwork->CanBeEditedBy(Session::$User)){
					throw new Exceptions\PermissionsInvalidException();
				}
			}
		}

		$artwork->Save($stagedImagePath);
	});

	if($isEditFormSubmission){
		UploadTempDirectory::RemoveUpload($stagedImagePath);

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
		UploadTempDirectory::RemoveUpload($stagedImagePath);

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
