<?
/**
 * POST		/polls
 * GET		/polls/:poll-url-name
 * PATCH	/polls/:poll-url-name
 */

if(Http::$Request->RelativePath == '/polls'){
	// If we got here, this is not a GET request.
	Http::$Request->Route(allowedHttpMethods: [Enums\HttpMethod::Get, Enums\HttpMethod::Post]);
}
else{
	try{
		Http::$Request->Route(resource: Poll::GetByUrlName(Http::$Request->QueryString->Get('poll-url-name')));
	}
	catch(Exceptions\NotFoundException){
		Template::ExitWithCode(Enums\HttpCode::NotFound);
	}
}
