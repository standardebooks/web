<?
/**
 * POST		/sessions
 */

if(Http::$Request->RelativePath == '/sessions'){
	// If we got here, this is not a GET request.
	Http::$Request->Route(allowedHttpMethods: [Enums\HttpMethod::Post]);
}
else{
	Http::$Request->Route();
}
