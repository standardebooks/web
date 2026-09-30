<?
/**
 * POST		/sessions
 */

if(Http::$Request->RelativeUri->getPath() == '/sessions'){
	// If we got here, this is not a GET request.
	Http::$Request->Route(allowedHttpMethods: [Enums\HttpMethod::Post]);
}
else{
	Http::$Request->Route();
}
