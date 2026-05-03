<?
/**
 * GET		/ebooks/:url-path
 */

if($_SERVER['SCRIPT_NAME'] == '/ebooks'){
	// If we got here, this is not a GET request.
	HttpInput::RouteRequest(allowedHttpMethods: [Enums\HttpMethod::Get]);
}
else{
	try{
		$urlPath = EBOOKS_IDENTIFIER_PREFIX . trim(str_replace('.', '', HttpInput::Str(GET, 'url-path') ?? ''), '/');

		$ebook = Ebook::GetByIdentifier($urlPath);

		HttpInput::RouteRequest(resource: $ebook);
	}
	catch(Exceptions\EbookNotFoundException){
		// Were we passed the author and a work but not the translator?
		// For example: <https://standardebooks.org/ebooks/omar-khayyam/the-rubaiyat-of-omar-khayyam>
		// Instead of: <https://standardebooks.org/ebooks/omar-khayyam/the-rubaiyat-of-omar-khayyam/edward-fitzgerald>.
		try{
			$ebook = Ebook::GetByIdentifierStartingWith($urlPath);

			// Found, redirect.
			http_response_code(Enums\HttpCode::MovedPermanently->value);
			header('location: ' . $ebook->Url);
			exit();
		}
		catch(Exceptions\EbookNotFoundException){
			// Still not found, continue.
		}

		Template::ExitWithCode(Enums\HttpCode::NotFound);
	}
}
