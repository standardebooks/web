<?
/**
 * DELETE	/spreadsheets/:spreadsheet-id
 */

use function Safe\session_start;

try{
	session_start();

	/** @var Spreadsheet $spreadsheet The `Spreadsheet` for this request, passed in from the router. */
	$spreadsheet = $resource ?? throw new Exceptions\SpreadsheetNotFoundException();

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditSpreadsheets){
		throw new Exceptions\InvalidPermissionsException();
	}

	$spreadsheet->Delete();

	$_SESSION['is-spreadsheet-deleted'] = true;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: /contribute/spreadsheets');
}
catch(Exceptions\SpreadsheetNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
