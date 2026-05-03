<?
/**
 * GET		/spreadsheets/:spreadsheet-id/delete
 */

use function Safe\session_start;

try{
	session_start();

	$spreadsheet = Spreadsheet::Get(HttpInput::Int(GET, 'spreadsheet-id'));

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditSpreadsheets){
		throw new Exceptions\InvalidPermissionsException();
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
	title: 'Delete ' . $spreadsheet->Title,
	highlight: 'contribute',
	css: ['/css/spreadsheets.css'],
	description: 'Delete the research spreadsheet “' . $spreadsheet->Title . '.”'
) ?>
<main>
	<section class="narrow">
		<nav class="breadcrumbs" aria-label="Breadcrumbs">
			<a href="/contribute/spreadsheets">Research Spreadsheets</a> →
			<a href="<?= $spreadsheet->Url ?>"><?= Formatter::EscapeHtml($spreadsheet->Title) ?></a> →
		</nav>
		<h1>Delete</h1>

		<form method="<?= Enums\HttpMethod::Post->value ?>" action="<?= $spreadsheet->Url ?>">
			<input type="hidden" name="_method" value="<?= Enums\HttpMethod::Delete->value ?>" />
			<p>Are you sure you want to delete the research spreadsheet “<?= Formatter::EscapeHtml($spreadsheet->Title) ?>”?</p>
			<div class="footer">
				<button class="delete">Delete</button>
			</div>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
