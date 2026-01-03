<?
use function Safe\session_start;
use function Safe\session_unset;

try{
	session_start();

	$exception = HttpInput::SessionObject('exception', Exceptions\AppException::class);
	$newsletterMailing = HttpInput::SessionObject('newsletter-mailing', NewsletterMailing::class);
	$addFooter = HttpInput::Bool(SESSION, 'add-footer') ?? false;
	$addEbooks = HttpInput::Bool(SESSION, 'add-ebooks') ?? false;

	if($newsletterMailing === null){
		$newsletterMailing = NewsletterMailing::Get(HttpInput::Int(GET, 'newsletter-mailing-id'));
	}

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditNewsletterMailings){
		throw new Exceptions\InvalidPermissionsException();
	}

	if($exception){
		// We got here because a submission had errors and the user has to try again.
		http_response_code(Enums\HttpCode::UnprocessableContent->value);
		session_unset();
	}
}
catch(Exceptions\NewsletterMailingNotFoundException){
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
		title: 'Edit Newsletter Mailing #' . $newsletterMailing->NewsletterMailingId,
		css: ['/css/newsletter-mailings.css'],
		description: 'Edit newsletter mailing #' . $newsletterMailing->NewsletterMailingId . ' in the Standard Ebooks system.',
) ?>
<main>
	<section class="narrow">
		<nav class="breadcrumbs" aria-label="Breadcrumbs">
			<a href="/newsletter-mailings">Newsletter Mailings</a> → #<?= $newsletterMailing->NewsletterMailingId ?> →
		</nav>
		<h1>Edit</h1>

		<?= Template::Error(exception: $exception) ?>

		<form method="<?= Enums\HttpMethod::Post->value ?>" action="<?= $newsletterMailing->Url ?>" autocomplete="off">
			<input type="hidden" name="_method" value="<?= Enums\HttpMethod::Patch->value ?>" />
			<?= Template::NewsletterMailingForm(newsletterMailing: $newsletterMailing, isEditForm: true, addFooter: $addFooter, addEbooks: $addEbooks) ?>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
