<?
/**
 * GET		/polls/:poll-url-name/edit
 */

use function Safe\session_start;
use function Safe\session_unset;

try{
	session_start();

	$originalPoll = Poll::GetByUrlName(Http::$Request->QueryString->Get('poll-url-name'));

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditPolls){
		throw new Exceptions\PermissionsInvalidException();
	}

	$exception = Http::$Request->Session->Get('poll/edit/exception', Exceptions\AppException::class);
	$poll = Http::$Request->Session->Get('poll', Poll::class) ?? $originalPoll;

	if($exception){
		// We got here because an operation had errors and the user has to try again.
		http_response_code(Enums\HttpCode::UnprocessableContent->value);
		session_unset();
	}
}
catch(Exceptions\PollNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\PermissionsInvalidException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
?><?= Template::Header(title: 'Edit ' . $originalPoll->Name, description: 'Edit the ' . $originalPoll->Name . ' poll.', css: ['/css/polls.css']) ?>
<main>
	<section class="narrow">
		<nav class="breadcrumbs" aria-label="Breadcrumbs">
			<a href="/polls">Polls</a> →
			<a href="<?= $originalPoll->Url ?>"><?= Formatter::EscapeHtml($originalPoll->Name) ?></a> →
		</nav>
		<h1>Edit</h1>

		<?= Template::Error(exception: $exception) ?>

		<form method="<?= Enums\HttpMethod::Post->value ?>" action="<?= $originalPoll->Url ?>" autocomplete="off">
			<input type="hidden" name="_method" value="<?= Enums\HttpMethod::Patch->value ?>" />
			<?= Template::PollForm(poll: $poll, isEditForm: true) ?>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
