<?
use function Safe\session_start;
use function Safe\session_unset;

try{
	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanCreateNewsletterMailings){
		throw new Exceptions\InvalidPermissionsException();
	}

	session_start();

	$exception = HttpInput::SessionObject('exception', Exceptions\AppException::class);
	$newsletterMailing = HttpInput::SessionObject('newsletter-mailing', NewsletterMailing::class) ?? new NewsletterMailing();
	$addFooter = HttpInput::Bool(SESSION, 'add-footer') ?? true;

	if($exception){
		// We got here because a submission had errors and the user has to try again.
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
		title: 'Create a Newsletter Mailing',
		css: ['/css/newsletter-mailings.css'],
		description: 'Create a new newsletter mailing in the Standard Ebooks system.',
) ?>
<main>
	<section class="narrow">
		<nav class="breadcrumbs">
			<a href="/newsletter-mailings">Newsletter Mailings</a> →
		</nav>
		<h1>Create a Newsletter Mailing</h1>

		<?= Template::Error(exception: $exception) ?>

		<form method="<?= Enums\HttpMethod::Post->value ?>" action="/newsletter-mailings" autocomplete="off">
			<?= Template::NewsletterMailingForm(newsletterMailing: $newsletterMailing, addFooter: $addFooter) ?>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
