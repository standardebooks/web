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
		throw new Exceptions\InvalidPermissionsException();
	}

	$spreadsheet = new Spreadsheet();
	$spreadsheet->FillFromHttpPost();

	$spreadsheet->Create();

	$_SESSION['is-spreadsheet-created'] = true;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: /contribute/spreadsheets');
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
catch(Exceptions\InvalidSpreadsheetException | Exceptions\SpreadsheetExistsException $ex){
	$_SESSION['spreadsheet'] = $spreadsheet;
	$_SESSION['exception'] = $ex;

	http_response_code(Enums\HttpCode::SeeOther->value);
	header('location: /spreadsheets/new');
}
