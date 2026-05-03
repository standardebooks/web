<?
/**
 * POST		/newsletter-subscriptions
 * DELETE	/users/:uuid/newsletter-subscriptions/:newsletter-id
 * PATCH	/users/:uuid/newsletter-subscriptions/:newsletter-id
 */

// Allow `_method` parameters in GET requests, since links to this resource are usually included in emails.
HttpInput::CalculateRequestMethod(true);

if($_SERVER['SCRIPT_NAME'] == '/newsletter-subscriptions'){
	// If we got here, this is not a GET request.
	HttpInput::RouteRequest(allowedHttpMethods: [Enums\HttpMethod::Post]);
}
else{
	try{
		HttpInput::RouteRequest(resource: NewsletterSubscription::GetByUserUuid(HttpInput::Str(GET, 'user-identifier'), HttpInput::Int(GET, 'newsletter-id')));
	}
	catch(Exceptions\NotFoundException){
		Template::ExitWithCode(Enums\HttpCode::NotFound);
	}
}
