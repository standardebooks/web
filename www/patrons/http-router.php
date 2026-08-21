<?
/**
 * POST		/users/:user-identifier/patrons
 */

try{
	$identifier = Http::$Request->QueryString->Get('user-identifier');
	Http::$Request->Route(resource: User::GetByIdentifier($identifier), allowedHttpMethods: [Enums\HttpMethod::Post]);
}
catch(Exceptions\NotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\AmbiguousUserException){
	Template::RedirectToDisambiguation($identifier);
}
