<?
/**
 * GET		/collections/:collection-url-nmae
 */

try{
	Http::$Request->Route(resource: Collection::GetByUrlName(Http::$Request->QueryString->Get('collection-url-name')));
}
catch(Exceptions\NotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}


