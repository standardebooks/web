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
catch(Exceptions\SeException){
	Template::Emit404();
}

session_start();

try{
	$artwork->Save(status: HttpInput::Str(POST, 'status'));

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
catch(Exceptions\SeException){
	http_response_code(422);
}
