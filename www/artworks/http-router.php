<?
/**
 * POST		/artworks
 * GET		/artworks/:artist-url-name/:artwork-url-name
 * PATCH	/artworks/:artist-url-name/:artwork-url-name
 */

if($_SERVER['SCRIPT_NAME'] == '/artworks'){
	// If we got here, this is not a GET request.
	HttpInput::RouteRequest(allowedHttpMethods: [Enums\HttpMethod::Get, Enums\HttpMethod::Post]);
}
else{
	try{
		try{
			$artwork = Artwork::GetByUrl(HttpInput::Str(GET, 'artist-url-name'), HttpInput::Str(GET, 'artwork-url-name'));
		}
		catch(Exceptions\ArtworkNotFoundException $ex){
			// We didn't find the artwork under this artist, does the artist exist under an alternate name?
			try{
				$artist = Artist::GetByAlternateUrlName(HttpInput::Str(GET, 'artist-url-name'));
				$artwork = Artwork::GetByUrl($artist->UrlName, HttpInput::Str(GET, 'artwork-url-name'));

				// Artwork found under an artist alternate name, redirect there and exit.
				http_response_code(Enums\HttpCode::MovedPermanently->value);
				header('location: ' . $artwork->Url);
				exit();
			}
			catch(Exceptions\ArtistNotFoundException){
				// The artwork is really not found, throw the original exception.
				throw $ex;
			}
		}

		HttpInput::RouteRequest(resource: $artwork);
	}
	catch(Exceptions\NotFoundException){
		Template::ExitWithCode(Enums\HttpCode::NotFound);
	}
}
