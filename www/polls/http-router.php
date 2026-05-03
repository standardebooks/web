<?
/**
 * GET		/polls/:poll-url-name
 */

try{
	HttpInput::RouteRequest(resource: Poll::GetByUrlName(HttpInput::Str(GET, 'poll-url-name')));
}
catch(Exceptions\NotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
