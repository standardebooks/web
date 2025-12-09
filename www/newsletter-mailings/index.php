<?
use function Safe\session_start;
use function Safe\session_unset;

session_start();

try{
	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanCreateNewsletterMailings && !Session::$User->Benefits->CanEditNewsletterMailings){
		throw new Exceptions\InvalidPermissionsException();
	}

	$isCreated = HttpInput::Bool(SESSION, 'is-newsletter-mailing-created') ?? false;
	$isSaved = HttpInput::Bool(SESSION, 'is-newsletter-mailing-saved') ?? false;

	$newsletterMailings = NewsletterMailing::GetAll();

	if($isCreated){
		http_response_code(Enums\HttpCode::Created->value);
	}

	if($isCreated || $isSaved){
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
		title: 'Newsletter Mailings',
		description: 'Manage newsletter mailings in the Standard Ebooks system.'
) ?>
<main>
	<section class="narrow has-hero">
		<h1>Newsletter Mailings</h1>

		<picture data-caption="Rack Pictures for Dr. Nones. William A. Mitchell, 1879">
			<source srcset="/images/rack-picture-for-dr-nones@2x.avif 2x, /images/rack-picture-for-dr-nones.avif 1x" type="image/avif"/>
			<source srcset="/images/rack-picture-for-dr-nones@2x.jpg 2x, /images/rack-picture-for-dr-nones.jpg 1x" type="image/jpg"/>
			<img src="/images/rack-picture-for-dr-nones@2x.jpg" alt="Postal mail attached to a billboard."/>
		</picture>

		<? if(Session::$User->Benefits->CanCreateNewsletterMailings){ ?>
			<ul role="menu">
				<li><a href="/newsletter-mailings/new">Create a newsletter mailing</a></li>
			</ul>
		<? } ?>

		<? if($isSaved){ ?>
			<p class="message success">Newsletter mailing saved!</p>
		<? } ?>

		<? if($isCreated){ ?>
			<p class="message success">Newsletter mailing created!</p>
		<? } ?>

		<ol>
			<? foreach($newsletterMailings as $newsletterMailing){ ?>
				<li>
					<p><?= Formatter::EscapeHtml($newsletterMailing->Subject) ?></p>
					<p><?= $newsletterMailing->SendOn->format(Enums\DateTimeFormat::FullDateTime->value) ?> • <i><?= ucfirst($newsletterMailing->Status->value) ?></i></p>
					<? if(Session::$User->Benefits->CanEditNewsletterMailings){ ?>
						<p>
							<a href="<?= $newsletterMailing->EditUrl ?>">Edit</a>
						</p>
					<? } ?>
				</li>
			<? } ?>
		</ol>
	</section>
</main>
<?= Template::Footer() ?>
