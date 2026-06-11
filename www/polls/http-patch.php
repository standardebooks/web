<?
/**
 * PATCH	/polls/:poll-url-name
 */

use function Safe\session_start;

try{
	session_start();

	/** @var Poll $poll The `Poll` for this request, passed in from the router. */
	$poll = $resource ?? throw new Exceptions\PollNotFoundException();
	$originalEditUrl = $poll->EditUrl;

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditPolls){
		throw new Exceptions\PermissionsInvalidException();
	}

	$poll->FillFromRequestBody();
	$poll->Save();

	$_SESSION['poll/edit/is-saved'] = true;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: ' . $poll->Url);
}
catch(Exceptions\PollNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\PermissionsInvalidException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
catch(Exceptions\PollInvalidException | Exceptions\PollExistsException $ex){
	$_SESSION['poll'] = $poll;
	$_SESSION['poll/edit/exception'] = $ex;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: ' . $originalEditUrl);
}
