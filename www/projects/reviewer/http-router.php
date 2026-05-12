<?
/**
 * GET		/ebooks/:url-path/projects/active/reviewer
 * GET		/projects/:project-id/reviewer
 */

try{
	$urlPath = Http::$Request->QueryString->Get('url-path');

	if($urlPath !== null){
		/** @var non-falsy-string $urlPath Contains the portion of the URL (without query string) that comes after `https://standardebooks.org/ebooks/`. */
		$urlPath = EBOOKS_IDENTIFIER_PREFIX . trim(str_replace('.', '', $urlPath), '/');

		$project = Project::GetByIdentifierAndIsActive($urlPath);
	}
	else{
		$project = Project::Get(Http::$Request->QueryString->Get('project-id', 'int'));
	}

	Http::$Request->Route(resource: $project);
}
catch(Exceptions\NotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
