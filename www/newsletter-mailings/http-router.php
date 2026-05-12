<?
/**
 * POST		/newsletter-mailings
 * PATCH	/newsletter-mailings/:newsletter-mailing-id
 */

if($_SERVER['SCRIPT_NAME'] == '/newsletter-mailings'){
	// If we got here, this is not a GET request.
	Http::$Request->Route(allowedHttpMethods: [Enums\HttpMethod::Get, Enums\HttpMethod::Post]);
}
else{
	try{
		$newsletterMailing = NewsletterMailing::Get(Http::$Request->QueryString->Get('newsletter-mailing-id', 'int'));

		Http::$Request->Route(resource: $newsletterMailing);
	}
	catch(Exceptions\NewsletterMailingNotFoundException){
		Template::ExitWithCode(Enums\HttpCode::NotFound);
	}
}
