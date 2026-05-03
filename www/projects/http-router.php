<?
/**
 * POST		/projects
 * PATCH	/projects/:project-id
 */

if($_SERVER['SCRIPT_NAME'] == '/projects'){
	// If we got here, this is not a GET request.
	HttpInput::RouteRequest(allowedHttpMethods: [Enums\HttpMethod::Get, Enums\HttpMethod::Post]);
}
else{
	try{
		HttpInput::RouteRequest(resource: Project::Get(HttpInput::Int(GET, 'project-id')));
	}
	catch(Exceptions\NotFoundException){
		Template::ExitWithCode(Enums\HttpCode::NotFound);
	}
}
