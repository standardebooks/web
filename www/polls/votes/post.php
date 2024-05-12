<?
use function Safe\session_unset;

try{
	HttpInput::ValidateRequestMethod([HttpMethod::Post]);

	session_start();

	$requestType = HttpInput::RequestType();

	$vote = new PollVote();

	$vote->PollItemId = HttpInput::Int(POST, 'pollitemid');

	$vote->Create(HttpInput::Str(POST, 'email'));

	session_unset();

	if($requestType == HttpRequestType::Web){
		$_SESSION['vote-created'] = $vote->UserId;
		http_response_code(303);
		header('Location: ' . $vote->Url);
	}
	else{
		// Access via HttpRequestType::Rest api; 201 CREATED with location
		http_response_code(201);
		header('Location: ' . $vote->Url);
	}
}
catch(Exceptions\InvalidPollVoteException $ex){
	if($requestType == HttpRequestType::Web){
		$_SESSION['vote'] = $vote;
		$_SESSION['exception'] = $ex;

		// Access via form; 303 redirect to the form, which will emit a 422 Unprocessable Entity
		http_response_code(303);
		header('Location: /polls/' . (HttpInput::Str(GET, 'pollurlname') ?? '') . '/votes/new');
	}
	else{
		// Access via HttpRequestType::Rest api; 422 Unprocessable Entity
		http_response_code(422);
	}
}
