<?
/**
 * GET		/polls/new
 */

use function Safe\session_start;
use function Safe\session_unset;

try{
	session_start();

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditPolls){
		throw new Exceptions\PermissionsInvalidException();
	}

	$exception = Http::$Request->Session->Get('poll/create/exception', Exceptions\AppException::class);
	$poll = Http::$Request->Session->Get('poll', Poll::class) ?? new Poll();

	if($exception){
		// We got here because an operation had errors and the user has to try again.
		http_response_code(Enums\HttpCode::UnprocessableContent->value);
		session_unset();
	}
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\PermissionsInvalidException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
?><?= Template::Header(title: 'Create a Poll', description: 'Create a poll for the Standard Ebooks Patrons Circle.', css: ['/css/polls.css']) ?>
<main>
	<section class="narrow">
		<nav class="breadcrumbs" aria-label="Breadcrumbs">
			<a href="/polls">Polls</a> →
		</nav>
		<h1>Create a Poll</h1>

		<?= Template::Error(exception: $exception) ?>

		<form method="<?= Enums\HttpMethod::Post->value ?>" action="/polls" autocomplete="off">
			<?= Template::PollForm(poll: $poll) ?>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
