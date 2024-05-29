<?= Template::Header(['title' => 'Accessibility', 'highlight' => 'about', 'description' => 'How we make Standard Ebooks accessible.']) ?>
<main>
	<section class="accessibility narrow">
		<h1>Accessibility</h1>
		<p>Our mission is to produce high-quality and free public domain ebooks, but none of that matters if people can’t read them. Consequently, we put a great deal of effort into making sure that our ebooks are as accessible as possible, for both the books’ content and the supporting metadata.</p>
		<section id="ebooks">
			<h2>Our ebooks</h2>
			<p>Our ebooks are built using semantic <abbr>XHTML</abbr>. All images and other non-textual content have alternative text to describe their contents, and where appropriate longer descriptions in a list of illustrations. All our ebooks are, to the best of our knowledge, compliant with <a href="https://www.w3.org/TR/WCAG22/"><abbr>WCAG</abbr> 2.2</a> level AA and <a href="https://www.w3.org/TR/epub-a11y-11/">EPUB Accessibility 1.1</a>.</p>
			<p>We use metadata to indicate the accessibility of our ebooks. Each title comes with an <a href="https://www.editeur.org/83/Overview/">ONIX</a> 3 declaration detailing its accessibility, and we also include <a href="https://schema.org">Schema.org</a> accessibility feature declarations in the package document.</p>
			<p>Ebooks obtained from our website will never use any <abbr title="Digital Rights Management">DRM</abbr> technology so you’re free to amend or process them to better fit your own needs.</p>
		</section>
		<section id="website">
			<h2>Our website</h2>
			<p>Our website is built using valid semantic <abbr>XHTML</abbr>. Non-decorative images have alternative text to describe their contents. The site has a dark mode if preferred, and if you’d like to override this you can do that from the <a href="https://standardebooks.org/settings">settings</a> page. To the best of our knowledge, the site is compliant with <abbr>WCAG</abbr> 2.2 level AA.</p>
		</section>
		<section id="support">
			<h2>Support</h2>
			<p>If you find that anything isn’t working for you then please <a href="https://groups.google.com/g/standardebooks">get in touch on our mailing list</a>. We’ll be happy to help directly, and will also investigate if there are any improvements we can make to our processes and corpus to better support you and other readers.</p>
		</section>
	</section>
</main>
<?= Template::Footer() ?>
