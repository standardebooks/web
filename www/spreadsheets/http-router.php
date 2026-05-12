<?
/**
 * POST		/spreadsheets
 * GET		/spreadsheets/:spreadsheet-id
 * PATCH	/spreadsheets/:spreadsheet-id
 * DELETE	/spreadsheets/:spreadsheet-id
 */

if(Http::$Request->RelativePath == '/spreadsheets'){
	// If we got here, this is not a GET request.
	Http::$Request->Route(allowedHttpMethods: [Enums\HttpMethod::Get, Enums\HttpMethod::Post]);
}
else{
	try{
		$spreadsheet = Spreadsheet::Get(Http::$Request->QueryString->Get('spreadsheet-id', 'int'));

		Http::$Request->Route(resource: $spreadsheet);
	}
	catch(Exceptions\NotFoundException){
		Template::ExitWithCode(Enums\HttpCode::NotFound);
	}
}
