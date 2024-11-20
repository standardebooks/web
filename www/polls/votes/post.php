<?
use function Safe\session_unset;

try{
	HttpInput::ValidateRequestMethod([Enums\HttpMethod::Post]);

	session_start();

	$requestType = HttpInput::GetRequestType();

	$vote = new PollVote();

	$vote->FillFromHttpPost();

	$vote->Create(HttpInput::Str(POST, 'email'));

	session_unset();

	if($requestType == Enums\HttpRequestType::Web){
		$_SESSION['is-vote-created'] = $vote->UserId;
		http_response_code(Enums\HttpCode::SeeOther->value);
		header('Location: ' . $vote->Url);
	}
	else{
		// Access via REST API; output `201 Created` with location.
		http_response_code(Enums\HttpCode::Created->value);
		header('Location: ' . $vote->Url);
	}
}
catch(Exceptions\InvalidPollVoteException $ex){
	if($requestType == Enums\HttpRequestType::Web){
		$_SESSION['vote'] = $vote;
		$_SESSION['exception'] = $ex;

		// Access via form; output 303 redirect to the form, which will emit a `422 Unprocessable Entity`.
		http_response_code(Enums\HttpCode::SeeOther->value);
		header('Location: /polls/' . (HttpInput::Str(GET, 'pollurlname') ?? '') . '/votes/new');
	}
	else{
		// Access via REST api; `422 Unprocessable Entity`.
		http_response_code(Enums\HttpCode::UnprocessableContent->value);
	}
}
