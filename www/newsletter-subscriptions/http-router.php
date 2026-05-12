<?
/**
 * POST		/newsletter-subscriptions
 * DELETE	/users/:uuid/newsletter-subscriptions/:newsletter-id
 * PATCH	/users/:uuid/newsletter-subscriptions/:newsletter-id
 */

// Allow `_method` parameters in GET requests, since links to this resource are usually included in emails.
Http::$Request->CalculateRequestMethod(true);

if($_SERVER['SCRIPT_NAME'] == '/newsletter-subscriptions'){
	// If we got here, this is not a GET request.
	Http::$Request->Route(allowedHttpMethods: [Enums\HttpMethod::Post]);
}
else{
	try{
		Http::$Request->Route(resource: NewsletterSubscription::GetByUserUuid(Http::$Request->QueryString->Get('user-identifier'), Http::$Request->QueryString->Get('newsletter-id', 'int')));
	}
	catch(Exceptions\NotFoundException){
		Template::ExitWithCode(Enums\HttpCode::NotFound);
	}
}
