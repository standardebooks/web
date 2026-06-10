<?
/**
 * POST		/polls
 */

use function Safe\session_start;

try{
	session_start();

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditPolls){
		throw new Exceptions\PermissionsInvalidException();
	}

	$poll = new Poll();
	$poll->FillFromRequestBody();
	$poll->Create();

	$_SESSION['is-poll-created'] = true;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: /polls');
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\PermissionsInvalidException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
catch(Exceptions\PollInvalidException | Exceptions\PollExistsException $ex){
	$_SESSION['poll'] = $poll;
	$_SESSION['poll/create/exception'] = $ex;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: /polls/new');
}
