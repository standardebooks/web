<?= Template::Header(['title' => 'We Couldn’t Find That Document', 'highlight' => '', 'description' => 'We couldn’t find that document.', 'is404' => true]) ?>
<main>
	<section class="narrow has-hero">
		<hgroup>
			<h1>We Couldn’t Find That Document</h1>
			<h2>This is 404 error.</h2>
		</hgroup>
		<picture data-caption="Blind Orion Searching for the Rising Sun. Nicolas Poussin, 1658">
			<source srcset="/images/blind-orion@2x.avif 2x, /images/blind-orion.avif 1x" type="image/avif"/>
			<source srcset="/images/blind-orion@2x.jpg 2x, /images/blind-orion.jpg 1x" type="image/jpg"/>
			<img src="/images/blind-orion@2x.jpg" alt="A classical city is aflame as people scramble in the foreground."/>
		</picture>
		<p>We couldn’t find a document at the URL you specified. Did you mistype the URL?</p>
		<p>If you arrived here from a link on the Standard Ebooks website, please <a href="/about#editor-in-chief">contact us</a> so that we can fix it.</p>
		<p>If you arrived here from a link elsewhere on the web, please contact the site you came from to ask them to fix their link.</p>
	</section>
</main>
<?= Template::Footer() ?>
