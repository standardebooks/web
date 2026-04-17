<?
use function Safe\session_start;

try{
	session_start();

	$poll = Poll::GetByUrlName(HttpInput::Str(GET, 'poll-url-name'));

	$requestType = HttpInput::GetRequestType();

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanVote){
		throw new Exceptions\InvalidPermissionsException();
	}

	$pollVote = new PollVote();
	$pollVote->Poll = $poll;
	$pollVote->UserId = Session::$User->UserId;
	$pollVote->User = Session::$User;

	$pollVote->FillFromHttpPost();

	$pollVote->Create();

	if($requestType == Enums\HttpRequestType::Web){
		$_SESSION['is-vote-created'] = $pollVote->UserId;
		http_response_code(Enums\HttpCode::SeeOther->value);
		header('Location: ' . $pollVote->Url);
	}
	else{
		// Access via REST API; output `201 Created` with location.
		http_response_code(Enums\HttpCode::Created->value);
		header('Location: ' . $pollVote->Url);
	}
}
catch(Exceptions\PollNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
catch(Exceptions\InvalidPollVoteException $ex){
	if($requestType == Enums\HttpRequestType::Web){
		$_SESSION['vote'] = $pollVote;
		$_SESSION['exception'] = $ex;

		// Access via form; output 303 redirect to the form, which will emit a `422 Unprocessable Entity`.
		http_response_code(Enums\HttpCode::SeeOther->value);
		header('Location: /polls/' . (HttpInput::Str(GET, 'poll-url-name') ?? '') . '/votes/new');
	}
	else{
		// Access via REST api; `422 Unprocessable Entity`.
		http_response_code(Enums\HttpCode::UnprocessableContent->value);
	}
}
