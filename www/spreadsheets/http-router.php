<?
/**
 * POST		/spreadsheets
 * GET		/spreadsheets/:spreadsheet-id
 * PATCH	/spreadsheets/:spreadsheet-id
 * DELETE	/spreadsheets/:spreadsheet-id
 */

if($_SERVER['SCRIPT_NAME'] == '/spreadsheets'){
	// If we got here, this is not a GET request.
	HttpInput::RouteRequest(allowedHttpMethods: [Enums\HttpMethod::Get, Enums\HttpMethod::Post]);
}
else{
	try{
		$spreadsheet = Spreadsheet::Get(HttpInput::Int(GET, 'spreadsheet-id'));

		HttpInput::RouteRequest(resource: $spreadsheet);
	}
	catch(Exceptions\NotFoundException){
		Template::ExitWithCode(Enums\HttpCode::NotFound);
	}
}
