<?
/**
 * POST		/spreadsheets
 */

use function Safe\session_start;

try{
	session_start();

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditSpreadsheets){
		throw new Exceptions\PermissionsInvalidException();
	}

	$spreadsheet = new Spreadsheet();
	$spreadsheet->FillFromRequestBody();

	$spreadsheet->Create();

	$_SESSION['spreadsheet/create/is-created'] = true;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: /contribute/spreadsheets');
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\PermissionsInvalidException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
catch(Exceptions\SpreadsheetInvalidException | Exceptions\SpreadsheetExistsException $ex){
	$_SESSION['spreadsheet'] = $spreadsheet;
	$_SESSION['spreadsheet/create/exception'] = $ex;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: /spreadsheets/new');
}
