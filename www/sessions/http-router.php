<?
/**
 * POST		/sessions
 */

if($_SERVER['SCRIPT_NAME'] == '/sessions'){
	// If we got here, this is not a GET request.
	Http::$Request->Route(allowedHttpMethods: [Enums\HttpMethod::Post]);
}
else{
	Http::$Request->Route();
}
