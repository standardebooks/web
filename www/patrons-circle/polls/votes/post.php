<?
require_once('Core.php');

use function Safe\preg_match;
use function Safe\session_unset;

if(HttpInput::RequestMethod() != HTTP_POST){
	http_response_code(405);
	exit();
}

session_start();

$requestType = HttpInput::RequestType();

$vote = new Vote();

try{
	$error = new Exceptions\ValidationException();

	$vote->PollItemId = HttpInput::Int(POST, 'pollitemid');

	$vote->Create(HttpInput::Str(POST, 'email', false));

	session_unset();

	if($requestType == WEB){
		http_response_code(303);
		header('Location: ' . $vote->PollItem->Poll->Url . '/votes/success');
	}
	else{
		// Access via REST api; 201 CREATED with location
		http_response_code(201);
		header('Location: ' . $vote->Url);
	}
}
catch(Exceptions\SeException $ex){
	// Validation failed
	if($requestType == WEB){
		$_SESSION['vote'] = $vote;
		$_SESSION['exception'] = $ex;

		// Access via form; 303 redirect to the form, which will emit a 400 BAD REQUEST
		http_response_code(303);
		header('Location: /patrons-circle/polls/' . HttpInput::Str(GET, 'pollurlname', false) . '/votes/new');
	}
	else{
		// Access via REST api; 400 BAD REQUEST
		http_response_code(400);
	}
}
