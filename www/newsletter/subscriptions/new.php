<?
use function Safe\session_start;
use function Safe\session_unset;

session_start();

/** @var NewsletterSubscription $subscription */
$subscription = $_SESSION['subscription'] ?? new NewsletterSubscription();
/** @var ?\Exception $exception */
$exception = $_SESSION['exception'] ?? null;

if($exception){
	http_response_code(Enums\HttpCode::UnprocessableContent->value);
	session_unset();
}

?><?= Template::Header(title: 'Subscribe to the Standard Ebooks newsletter', highlight: 'newsletter', description: 'Subscribe to the Standard Ebooks newsletter to receive occasional updates about the project.') ?>
<main>
	<section class="narrow has-hero">
		<hgroup>
			<h1>Subscribe to the Newsletter</h1>
			<p>to receive missives from the vanguard of digital literature</p>
		</hgroup>
		<?= Template::DonationCounter() ?>
		<?= Template::DonationProgress() ?>
		<picture data-caption="The Newsletter. William John Wainwright, 1888">
			<source srcset="/images/the-newsletter@2x.avif 2x, /images/the-newsletter.avif 1x" type="image/avif"/>
			<source srcset="/images/the-newsletter@2x.jpg 2x, /images/the-newsletter.jpg 1x" type="image/jpg"/>
			<img src="/images/the-newsletter@2x.jpg" alt="An old man in Renaissance-era costume reading a sheet of paper."/>
		</picture>
		<p>Subscribe to receive news, updates, and more from Standard Ebooks. Your information will never be shared, and you can unsubscribe at any time.</p>

		<?= Template::Error(exception: $exception) ?>

		<form action="/newsletter/subscriptions" method="<?= Enums\HttpMethod::Post->value ?>">
			<label class="automation-test"><? /* Test for spam bots filling out all fields */ ?>
				<input type="text" name="automation-test" value="" maxlength="80" />
			</label>
			<label>Your email address
				<input type="email" name="email" value="<?= Formatter::EscapeHtml($subscription->User->Email ?? '') ?>" maxlength="80" required="required" />
			</label>
			<label class="icon captcha">
				Type the letters in the <abbr class="acronym">CAPTCHA</abbr> image
				<div>
					<input type="text" name="captcha" required="required" autocomplete="off" />
					<img src="/images/captcha" alt="A visual CAPTCHA." height="<?= CAPTCHA_IMAGE_HEIGHT ?>" width="<?= CAPTCHA_IMAGE_WIDTH ?>" />
				</div>
			</label>
			<fieldset>
				<p>What kind of email would you like to receive?</p>
				<ul>
					<li>
						<label>
							<input type="hidden" name="is-subscribed-to-newsletter" value="false" />
							<input type="checkbox" value="true" name="is-subscribed-to-newsletter"<? if($subscription->IsSubscribedToNewsletter){ ?> checked="checked"<? } ?> />The monthly Standard Ebooks newsletter
						</label>
					</li>
					<li>
						<label>
							<input type="hidden" name="is-subscribed-to-summary" value="false" />
							<input type="checkbox" value="true" name="is-subscribed-to-summary"<? if($subscription->IsSubscribedToSummary){ ?> checked="checked"<? } ?> />A monthly summary of new ebook releases
						</label>
					</li>
				</ul>
			</fieldset>
			<button>Subscribe</button>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
