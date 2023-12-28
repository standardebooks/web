<?
try{
	session_start();

	if(HttpInput::RequestMethod() != HTTP_PATCH){
		throw new Exceptions\InvalidRequestException();
	}

	if($GLOBALS['User'] === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!$GLOBALS['User']->Benefits->CanReviewArtwork){
		throw new Exceptions\InvalidPermissionsException();
	}

	$artwork = Artwork::Get(HttpInput::Int(GET, 'artworkid'));
	$artwork->Status = HttpInput::Str(POST, 'status', false);
	$artwork->ReviewerUserId = $GLOBALS['User']->UserId;
	$artwork->Save();

	$_SESSION['artwork-id'] = $artwork->ArtworkId;
	$_SESSION['status'] = $artwork->Status;

	http_response_code(303);
	header('Location: /admin/artworks');
}
catch(Exceptions\InvalidRequestException){
	http_response_code(405);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::Emit403(); // No permissions to submit artwork
}
catch(Exceptions\ArtworkNotFoundException){
	Template::Emit404();
}
catch(Exceptions\AppException $exception){
	$_SESSION['exception'] = $exception;

	http_response_code(303);
	header('Location: /admin/artworks/' . $artwork->ArtworkId);
}
