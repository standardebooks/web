<?
/**
 * POST		/sessions
 */

if($_SERVER['SCRIPT_NAME'] == '/sessions'){
	// If we got here, this is not a GET request.
	HttpInput::RouteRequest(allowedHttpMethods: [Enums\HttpMethod::Get, Enums\HttpMethod::Post]);
}
else{
	HttpInput::RouteRequest();
}
