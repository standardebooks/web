<?
require_once('Core.php');

if(HttpInput::RequestMethod() != HTTP_PATCH){
	http_response_code(405);
	exit();
}

$artworkId = HttpInput::Int(GET, 'artworkid');

try{
	$artwork = Artwork::Get($artworkId);
}
catch(Exceptions\SeException $ex){
	Template::Emit404();
}

session_start();

try{
	$newStatus = HttpInput::Str(POST, 'status');
	switch($newStatus){
		case 'approved':
			$artwork->Status = 'approved';
			$_SESSION['approved-message'] = '“' . $artwork->Name . '” approved.';
			break;
		case 'declined':
			$artwork->Status = 'declined';
			$_SESSION['declined-message'] = '“' . $artwork->Name . '” declined.';
			break;
	}

	$artwork->Save();

	http_response_code(303);
	header('Location: /admin/artworks');
}
catch(Exceptions\SeException $ex){
	http_response_code(422);
}
