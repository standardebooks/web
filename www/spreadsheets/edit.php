<?
/**
 * GET		/spreadsheets/:spreadsheet-id/edit
 */

use function Safe\session_start;
use function Safe\session_unset;

try{
	session_start();

	$originalSpreadsheet = Spreadsheet::Get(HttpInput::Int(GET, 'spreadsheet-id'));

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditSpreadsheets){
		throw new Exceptions\InvalidPermissionsException();
	}

	$exception = HttpInput::SessionObject('exception', Exceptions\AppException::class);
	$spreadsheet = HttpInput::SessionObject('spreadsheet', Spreadsheet::class) ?? $originalSpreadsheet;

	if($exception){
		// We got here because an operation had errors and the user has to try again.
		http_response_code(Enums\HttpCode::UnprocessableContent->value);
		session_unset();
	}
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
?>
<?= Template::Header(
	title: 'Edit - ' . $originalSpreadsheet->Title,
	highlight: 'contribute',
	css: ['/css/spreadsheets.css'],
	description: 'Edit the research spreadsheet “' . $originalSpreadsheet->Title . '.”'
) ?>
<main>
	<section class="narrow">
		<nav class="breadcrumbs" aria-label="Breadcrumbs">
			<a href="/contribute/spreadsheets">Research Spreadsheets</a> →
			<a href="<?= $originalSpreadsheet->Url ?>"><?= Formatter::EscapeHtml($originalSpreadsheet->Title) ?></a> →
		</nav>
		<h1>Edit</h1>

		<?= Template::Error(exception: $exception) ?>

		<form method="<?= Enums\HttpMethod::Post->value ?>" action="<?= $originalSpreadsheet->Url ?>" autocomplete="off">
			<input type="hidden" name="_method" value="<?= Enums\HttpMethod::Patch->value ?>" />
			<?= Template::SpreadsheetForm(spreadsheet: $spreadsheet, isEditForm: true) ?>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
