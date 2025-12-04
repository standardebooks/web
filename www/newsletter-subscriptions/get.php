<?
/**
 * GET /users/<uuid>/newsletter-subscriptions
 * List all `NewsletterSubscriptions` for the given `User`.
 */
use function Safe\session_start;
use function \Safe\session_unset;

session_start();

try{
	// If a bot filled out this form, we'll get a random UUID; show success.
	if(HttpInput::Bool(SESSION, 'is-bot') ?? false){
		$isCreated = true;
		$isConfirmed = false;
		$isDeleted = false;
		$user = new User();
		$user->NewsletterSubscriptions = [];
		session_unset();
	}
	else{
		$user = User::GetByUuid(HttpInput::Str(GET, 'user-identifier'));

		$isCreated = HttpInput::Bool(SESSION, 'is-newsletter-subscription-created') ?? false;
		$isConfirmed = HttpInput::Bool(SESSION, 'are-newsletter-subscriptions-confirmed') ?? false;
		$isDeleted = HttpInput::Bool(SESSION, 'is-newsletter-subscriptions-deleted') ?? false;

		if($isCreated){
			http_response_code(Enums\HttpCode::Created->value);
		}

		if($isDeleted){
			$newsletterName = HttpInput::Str(SESSION, 'newsletter-name');
		}

		if($isCreated || $isConfirmed || $isDeleted){
			session_unset();
		}
	}
}
catch(Exceptions\UserNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
?>
<?= Template::Header(title: 'Your Newsletter Subscriptions', highlight: 'newsletter', description: 'Your subscription to the Standard Ebooks newsletter.') ?>
<main>
	<section class="narrow">
		<h1>Your Newsletter Subscriptions</h1>
		<? if($isConfirmed){ ?>
			<p class="message success">Your subscription has been confirmed!</p>
		<? } ?>

		<? if($isDeleted){ ?>
			<p class="message success">You have been unsubscribed<? if(isset($newsletterName)){ ?> from <?= Formatter::EscapeHtml($newsletterName) ?><? } ?>!</p>
		<? } ?>

		<? if($isCreated){ ?>
			<div class="message success">
				<p><strong>Almost Done!</strong></p>
				<p>Please check your email inbox for a confirmation email containing a link to finalize your subscription to our newsletter.</p>
				<p>Your subscription won’t be activated until you click that link—this helps us prevent spam. Thank you!</p>
			</div>
		<? } ?>

		<? if(sizeof($user->NewsletterSubscriptions) == 0){ ?>
			<p class="empty-notice">You’re not subscribed to any newsletters.</p>
		<? }else{ ?>
			<p>You’re subscribed to the following newsletters:</p>
			<ul>
				<? foreach($user->NewsletterSubscriptions as $newsletterSubscription){ ?>
					<li>
						<p><?= Formatter::EscapeHtml($newsletterSubscription->Newsletter->Name) ?><? if(!$newsletterSubscription->IsConfirmed){ ?> — <i>Confirmation pending</i><? } ?> — <a href="<?= $newsletterSubscription->DeleteUrl ?>">Unsubscribe</a></p>
					</li>
				<? } ?>
			</ul>
		<? } ?>
	</section>
</main>
<?= Template::Footer() ?>
