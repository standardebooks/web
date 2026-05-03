<?
/**
 * GET		/spreadsheets/:spreadsheet-id
 */

try{
	/** @var Spreadsheet $spreadsheet The `Spreadsheet` for this request, passed in from the router. */
	$spreadsheet = $resource ?? throw new Exceptions\SpreadsheetNotFoundException();

	header('location: ' . $spreadsheet->ExternalUrl);
}
catch(Exceptions\SpreadsheetNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
