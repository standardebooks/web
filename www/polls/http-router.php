<?
/**
 * GET		/polls/:poll-url-name
 */

try{
	Http::$Request->Route(resource: Poll::GetByUrlName(Http::$Request->QueryString->Get('poll-url-name')));
}
catch(Exceptions\NotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
