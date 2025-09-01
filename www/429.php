<?= Template::Header(title: 'You Have Exceeded Your Rate Limit', description: 'You’ve downloaded too many ebooks in too short a time, and must wait before trying again.') ?>
<main>
	<section class="narrow has-hero">
		<hgroup>
			<h1>You Have Exceeded Your Rate Limit</h1>
			<p>This is a 429 error.</p>
		</hgroup>
		<picture data-caption="The Triumph of Silenus. Nicolas Poussin, c. 1665">
			<source srcset="/images/the-triumph-of-silenus@2x.avif 2x, /images/the-triumph-of-silenus.avif 1x" type="image/avif"/>
			<source srcset="/images/the-triumph-of-silenus@2x.jpg 2x, /images/the-triumph-of-silenus.jpg 1x" type="image/jpg"/>
			<img src="/images/the-triumph-of-silenus@2x.jpg" alt="Silenus and his satyrs engage in a gluttonous bacchanal."/>
		</picture>
		<p>You’ve downloaded too many ebooks in too short a time. Please wait before trying again.</p>
		<p><strong>If you’re scripting ebook downloads and you’re a <a href="/donate#patrons-circle">Patrons Circle</a> member,</strong> pass your email address via HTTP Basic authentication when making your request to bypass the rate limit.</p>
	</section>
</main>
<?= Template::Footer() ?>
