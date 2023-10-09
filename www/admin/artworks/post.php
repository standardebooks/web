<?
if(HttpInput::RequestMethod() != HTTP_PATCH){
	http_response_code(405);
	exit();
}

$artworkId = HttpInput::Int(GET, 'artworkid');

try{
	$artwork = Artwork::Get($artworkId);
}
catch(Exceptions\AppException){
	Template::Emit404();
}

session_start();

try{
	$status = HttpInput::Str(POST, 'status', false);
	if($status === null){
		throw new \Exceptions\InvalidRequestException('Empty or invalid status');
	}

	$artwork->Save($status);

	switch($artwork->Status){
		case 'approved':
			$_SESSION['approved-message'] = '“' . $artwork->Name . '” approved.';
			break;
		case 'declined':
			$_SESSION['declined-message'] = '“' . $artwork->Name . '” declined.';
			break;
	}

	http_response_code(303);
	header('Location: /admin/artworks');
}
catch(Exceptions\AppException){
	http_response_code(422);
}
