<?
/**
 * GET		/donations/thanks
 */

use Safe\DateTimeImmutable;

// Hide general donation messages for one month after a donation or subscription checkout.
setcookie('hide-donation-messages', 'true', ['expires' => intval((new DateTimeImmutable('+1 month'))->format(Enums\DateTimeFormat::UnixTimestamp->value)), 'path' => '/', 'domain' => SITE_DOMAIN, 'secure' => true, 'httponly' => true, 'samesite' => 'Lax']);

$isPatronsCircleMembership = Http::$Request->QueryString->Get('is-patrons-circle-membership', 'bool') ?? false;
?>
<?= Template::Header(title: 'Thank You', description: 'Thank you for supporting Standard Ebooks!', css: ['/css/donations.css']) ?>
<main>
	<section class="narrow has-hero">
		<hgroup>
			<h1>Thank You</h1>
			<p>for supporting the literate arts!</p>
		</hgroup>
		<picture data-caption="Hip, Hip, Hurrah! Peder Severin Krøyer, 1888">
			<source srcset="/images/hip-hip-hurrah@2x.avif 2x, /images/hip-hip-hurrah.avif 1x" type="image/avif"/>
			<source srcset="/images/hip-hip-hurrah@2x.jpg 2x, /images/hip-hip-hurrah.jpg 1x" type="image/jpeg"/>
			<img src="/images/hip-hip-hurrah@2x.jpg" alt="People raising their glasses around a table at an outdoor celebration."/>
		</picture>
		<p>Your donation has been received, and will go towards continuing our work of creating beautiful ebook editions of the world’s finest literature, to distribute for free.</p>
		<? if($isPatronsCircleMembership){ ?>
			<section id="benefits">
				<h2>Your Patrons Circle benefits</h2>
				<ul>
					<li><p><a href="/sessions/new">Log in</a> to activate your Patrons Circle benefits. Once logged in, you can access <a href="/bulk-downloads">bulk ebook downloads</a> and <a href="/polls">vote in our polls</a>.</p></li>
					<li><p>To access our <a href="/feeds">ebook feeds</a> in an ereading app or device, or an RSS reader, enter your email address when prompted and leave the password field blank.</p></li>
				</ul>
			</section>
		<? } ?>
		<p class="button-row center">
			<? if($isPatronsCircleMembership){ ?>
				<a class="button" href="/sessions/new">Log in to the Patrons Circle</a>
			<? } ?>
			<a class="button next" href="/ebooks">Back to the ebooks</a>
		</p>
	</section>
</main>
<?= Template::Footer() ?>
