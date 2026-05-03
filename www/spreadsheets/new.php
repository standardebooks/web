<?
/**
 * GET		/spreadsheets/new
 */

use function Safe\session_start;
use function Safe\session_unset;

try{
	session_start();

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditSpreadsheets){
		throw new Exceptions\InvalidPermissionsException();
	}

	$exception = HttpInput::SessionObject('exception', Exceptions\AppException::class);
	$spreadsheet = HttpInput::SessionObject('spreadsheet', Spreadsheet::class) ?? new Spreadsheet();

	if($exception){
		// We got here because an operation had errors and the user has to try again.
		http_response_code(Enums\HttpCode::UnprocessableContent->value);
		session_unset();
	}
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
?>
<?= Template::Header(
	title: 'Create a Spreadsheet',
	highlight: 'contribute',
	css: ['/css/spreadsheets.css'],
	description: 'Create a research spreadsheet in the Standard Ebooks system.'
) ?>
<main>
	<section class="narrow">
		<nav class="breadcrumbs" aria-label="Breadcrumbs">
			<a href="/contribute/spreadsheets">Research Spreadsheets</a> →
		</nav>
		<h1>Create a Spreadsheet</h1>

		<?= Template::Error(exception: $exception) ?>

		<form method="<?= Enums\HttpMethod::Post->value ?>" action="/spreadsheets" autocomplete="off">
			<?= Template::SpreadsheetForm(spreadsheet: $spreadsheet) ?>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
