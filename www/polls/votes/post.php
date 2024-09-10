<?
use function Safe\session_unset;

try{
	HttpInput::ValidateRequestMethod([Enums\HttpMethod::Post]);

	session_start();

	$requestType = HttpInput::GetRequestType();

	$vote = new PollVote();

	$vote->PollItemId = HttpInput::Int(POST, 'pollitemid');

	$vote->Create(HttpInput::Str(POST, 'email'));

	session_unset();

	if($requestType == Enums\HttpRequestType::Web){
		$_SESSION['is-vote-created'] = $vote->UserId;
		http_response_code(303);
		header('Location: ' . $vote->Url);
	}
	else{
		// Access via Enums\HttpRequestType::Rest api; 201 CREATED with location
		http_response_code(201);
		header('Location: ' . $vote->Url);
	}
}
catch(Exceptions\InvalidPollVoteException $ex){
	if($requestType == Enums\HttpRequestType::Web){
		$_SESSION['vote'] = $vote;
		$_SESSION['exception'] = $ex;

		// Access via form; 303 redirect to the form, which will emit a 422 Unprocessable Entity
		http_response_code(303);
		header('Location: /polls/' . (HttpInput::Str(GET, 'pollurlname') ?? '') . '/votes/new');
	}
	else{
		// Access via Enums\HttpRequestType::Rest api; 422 Unprocessable Entity
		http_response_code(422);
	}
}
