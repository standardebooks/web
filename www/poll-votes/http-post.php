<?
use function Safe\session_start;

try{
	session_start();

	/** @var Poll $poll The `Poll` for this request, passed in from the router. */
	$poll = $resource ?? throw new Exceptions\PollNotFoundException();

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanVote){
		throw new Exceptions\PermissionsInvalidException();
	}

	$pollVote = new PollVote();
	$pollVote->Poll = $poll;
	$pollVote->UserId = Session::$User->UserId;
	$pollVote->User = Session::$User;

	$pollVote->FillFromRequestBody();

	$pollVote->Create();

	$_SESSION['is-vote-created'] = $pollVote->UserId;
	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: ' . $pollVote->Url);
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
catch(Exceptions\PollVoteInvalidException $ex){
	$_SESSION['vote'] = $pollVote;
	$_SESSION['poll-vote/create/exception'] = $ex;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: /polls/' . (Http::$Request->QueryString->Get('poll-url-name') ?? '') . '/votes/new');
}
