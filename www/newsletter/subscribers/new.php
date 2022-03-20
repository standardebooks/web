<?
require_once('Core.php');

use function Safe\session_unset;

session_start();

$subscriber = $_SESSION['subscriber'] ?? new NewsletterSubscriber();
$exception = $_SESSION['exception'] ?? null;

if($exception){
	http_response_code(400);
	session_unset();
}

?><?= Template::Header(['title' => 'Subscribe to the Standard Ebooks newsletter', 'highlight' => 'newsletter', 'description' => 'Subscribe to the Standard Ebooks newsletter to receive occasional updates about the project.']) ?>
<main>
	<article class="has-hero">
		<hgroup>
			<h1>Subscribe to the Newsletter</h1>
			<h2>to receive missives from the vanguard of digital literature</h2>
		</hgroup>
		<picture>
			<source srcset="/images/the-newsletter@2x.avif 2x, /images/the-newsletter.avif 1x" type="image/avif"/>
			<source srcset="/images/the-newsletter@2x.jpg 2x, /images/the-newsletter.jpg 1x" type="image/jpg"/>
			<img src="/images/the-newsletter@2x.jpg" alt="An old man in Renaissance-era costume reading a sheet of paper."/>
		</picture>
		<p>Subscribe to receive news, updates, and more from Standard Ebooks. Your information will never be shared, and you can unsubscribe at any time.</p>

		<?= Template::Error(['exception' => $exception]) ?>

		<form action="/newsletter/subscribers" method="post">
			<label class="automation-test"><? /* Test for spam bots filling out all fields */ ?>
				<input type="text" name="automationtest" value="" maxlength="80" />
			</label>
			<label class="email">Email
				<input type="email" name="email" value="<?= Formatter::ToPlainText($subscriber->Email) ?>" maxlength="80" required="required" />
			</label>
			<label class="text">First name
				<input type="text" name="firstname" autocomplete="given-name" maxlength="80" value="<?= Formatter::ToPlainText($subscriber->FirstName) ?>" />
			</label>
			<label class="text">Last name
				<input type="text" name="lastname" autocomplete="family-name" maxlength="80" value="<?= Formatter::ToPlainText($subscriber->LastName) ?>" />
			</label>
			<label class="captcha">
				Type the letters in the image
				<div>
					<input type="text" name="captcha" required="required" />
					<img src="/images/captcha" alt="A visual CAPTCHA." height="<?= CAPTCHA_IMAGE_HEIGHT ?>" width="<?= CAPTCHA_IMAGE_WIDTH ?>" />
				</div>
			</label>
			<fieldset>
				<p>What kind of email would you like to receive?</p>
				<ul>
					<li>
						<label class="checkbox"><input type="checkbox" value="1" name="newsletter"<? if($subscriber->IsSubscribedToNewsletter){ ?> checked="checked"<? } ?> />The occasional Standard Ebooks newsletter</label>
					</li>
					<li>
						<label class="checkbox"><input type="checkbox" value="1" name="monthlysummary"<? if($subscriber->IsSubscribedToSummary){ ?> checked="checked"<? } ?> />A monthly summary of new ebook releases</label>
					</li>
				</ul>
			</fieldset>
			<button>Subscribe</button>
		</form>
	</article>
</main>
<?= Template::Footer() ?>
