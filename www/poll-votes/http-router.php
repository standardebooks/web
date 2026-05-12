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
		Http::$Request->Route(resource: Poll::GetByUrlName(Http::$Request->QueryString->Get('poll-url-name')), allowedHttpMethods: [Enums\HttpMethod::Get, Enums\HttpMethod::Post]);
	}
	catch(Exceptions\NotFoundException){
		Template::ExitWithCode(Enums\HttpCode::NotFound);
	}
}
else{
	try{
		Http::$Request->Route(resource: PollVote::Get(Http::$Request->QueryString->Get('poll-url-name'), Http::$Request->QueryString->Get('user-id', 'int')));
	}
	catch(Exceptions\NotFoundException){
		Template::ExitWithCode(Enums\HttpCode::NotFound);
	}
}
