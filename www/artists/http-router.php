<?
/**
 * GET		/artworks/:artist-url-name
 * DELETE	/artworks/:artist-url-name
 */

try{
	Http::$Request->Route(resource: Artist::GetByUrlName(Http::$Request->QueryString->Get('artist-url-name')));
}
catch(Exceptions\NotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
