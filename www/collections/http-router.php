<?
/**
 * GET		/collections/:collection-url-nmae
 */

try{
	HttpInput::RouteRequest(resource: Collection::GetByUrlName(HttpInput::Str(GET, 'collection-url-name')));
}
catch(Exceptions\NotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}


