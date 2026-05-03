<?
/**
 * POST		/users
 * GET		/users/:user-identifier
 * PATCH	/users/:user-identifier
 */

if($_SERVER['SCRIPT_NAME'] == '/users'){
	// If we got here, this is not a GET request.
	HttpInput::RouteRequest(allowedHttpMethods: [Enums\HttpMethod::Get, Enums\HttpMethod::Post]);
}
else{
	try{
		$identifier = HttpInput::Str(GET, 'user-identifier');
		HttpInput::RouteRequest(resource: User::GetByIdentifier($identifier));
	}
	catch(Exceptions\NotFoundException){
		Template::ExitWithCode(Enums\HttpCode::NotFound);
	}
	catch(Exceptions\AmbiguousUserException){
		Template::RedirectToDisambiguation($identifier);
	}
}
