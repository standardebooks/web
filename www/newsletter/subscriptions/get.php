<?
use function \Safe\session_unset;

session_start();

$subscription = new NewsletterSubscription();
$created = false;
$updated = false;
$confirmed = false;

try{
	if(isset($_SESSION['is-subscription-created']) && $_SESSION['is-subscription-created'] == 0){
		$created = true;
	}
	else{
		$subscription = NewsletterSubscription::Get(HttpInput::Str(GET, 'uuid'));

		if(isset($_SESSION['is-subscription-created']) && $_SESSION['is-subscription-created'] == $subscription->UserId){
			$created = true;
		}

		if(isset($_SESSION['subscription-updated']) && $_SESSION['subscription-updated'] == $subscription->UserId){
			$updated = true;
		}

		if(isset($_SESSION['subscription-confirmed']) && $_SESSION['subscription-confirmed'] == $subscription->UserId){
			$confirmed = true;
		}
	}

	if($created || $updated || $confirmed){
		session_unset();
	}

	if($created){
		// HTTP 201 Created
		http_response_code(Enums\HttpCode::Created->value);
	}
}
catch(Exceptions\AppException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}

?><?= Template::Header(['title' => 'Your Subscription to the Standard Ebooks Newsletter', 'highlight' => 'newsletter', 'description' => 'Your subscription to the Standard Ebooks newsletter.']) ?>
<main>
	<section class="narrow">
		<? if($subscription->IsConfirmed){ ?>
			<h1>Your Standard Ebooks Newsletter Subscription</h1>
		<? if($updated){ ?>
			<p class="message success">Your settings have been saved!</p>
		<? } ?>
		<? if($confirmed){ ?>
			<p class="message success">Your subscription has been confirmed!</p>
		<? } ?>
		<p>You’re set to receive the following newsletters:</p>
		<ul>
			<? if($subscription->IsSubscribedToSummary){ ?>
				<li>
					<p>A monthly summary of new ebook releases</p>
				</li>
			<? } ?>
			<? if($subscription->IsSubscribedToNewsletter){ ?>
				<li>
					<p>The occasional Standard Ebooks newsletter</p>
				</li>
			<? } ?>
		</ul>
		<p class="button-row narrow">
			<a href="<?= $subscription->Url ?>/delete" class="button">Unsubscribe</a>
		</p>
		<? }else{ ?>
			<h1>Almost Done!</h1>
			<p>Please check your email inbox for a confirmation email containing a link to finalize your subscription to our newsletter.</p>
			<p>Your subscription won’t be activated until you click that link—this helps us prevent spam. Thank you!</p>
		<? } ?>
	</section>
</main>
<?= Template::Footer() ?>
