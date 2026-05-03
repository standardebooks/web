<?
/**
 * GET		/artworks/:artist-url-name
 * DELETE	/artworks/:artist-url-name
 */

try{
	HttpInput::RouteRequest(resource: Artist::GetByUrlName(HttpInput::Str(GET, 'artist-url-name')));
}
catch(Exceptions\NotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
