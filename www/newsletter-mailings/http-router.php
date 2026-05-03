<?
/**
 * POST		/newsletter-mailings
 * PATCH	/newsletter-mailings/:newsletter-mailing-id
 */

if($_SERVER['SCRIPT_NAME'] == '/newsletter-mailings'){
	// If we got here, this is not a GET request.
	HttpInput::RouteRequest(allowedHttpMethods: [Enums\HttpMethod::Get, Enums\HttpMethod::Post]);
}
else{
	try{
		$newsletterMailing = NewsletterMailing::Get(HttpInput::Int(GET, 'newsletter-mailing-id'));

		HttpInput::RouteRequest(resource: $newsletterMailing);
	}
	catch(Exceptions\NewsletterMailingNotFoundException){
		Template::ExitWithCode(Enums\HttpCode::NotFound);
	}
}
