<?
/**
 * POST		/projects
 * PATCH	/projects/:project-id
 */

if(Http::$Request->RelativePath == '/projects'){
	// If we got here, this is not a GET request.
	Http::$Request->Route(allowedHttpMethods: [Enums\HttpMethod::Get, Enums\HttpMethod::Post]);
}
else{
	try{
		Http::$Request->Route(resource: Project::Get(Http::$Request->QueryString->Get('project-id', 'int')));
	}
	catch(Exceptions\NotFoundException){
		Template::ExitWithCode(Enums\HttpCode::NotFound);
	}
}
