<?
/**
 * POST		/polls/:poll-url-name/votes
 * GET		/polls/:poll-url-name/votes/:user-id
 */

use function Safe\preg_match;

/** @var string $requestUri */
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
if(preg_match('/^\/polls\/[^\/\.]+\/votes$/ius', $requestUri)){
	// POSTing a `PollVote`.
	try{
		HttpInput::RouteRequest(resource: Poll::GetByUrlName(HttpInput::Str(GET, 'poll-url-name')), allowedHttpMethods: [Enums\HttpMethod::Get, Enums\HttpMethod::Post]);
	}
	catch(Exceptions\NotFoundException){
		Template::ExitWithCode(Enums\HttpCode::NotFound);
	}
}
else{
	try{
		HttpInput::RouteRequest(resource: PollVote::Get(HttpInput::Str(GET, 'poll-url-name'), HttpInput::Int(GET, 'user-id')));
	}
	catch(Exceptions\NotFoundException){
		Template::ExitWithCode(Enums\HttpCode::NotFound);
	}
}
