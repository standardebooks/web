<?
require_once('Core.php');
?><?= Template::Header(['title' => 'About Standard Ebooks', 'highlight' => 'about', 'description' => 'The Standard Ebooks project is a volunteer driven, not-for-profit effort to produce a collection of high quality, carefully formatted, accessible, open source, and free public domain ebooks that meet or exceed the quality of commercially produced ebooks. The text and cover art in our ebooks is already believed to be in the public domain, and Standard Ebook dedicates its own work to the public domain, thus releasing whole ebooks files themselves into the public domain.']) ?>
<main>
	<article>
		<h1>About Standard Ebooks</h1>
		<section id="goals">
			<h2>Goals</h2>
			<ol>
				<li>
					<p>Produce ebooks that embrace the latest ebook technology standards.</p>
				</li>
				<li>
					<p>Produce ebooks aimed at the sensibilities of modern readers that rival commercially-available ebooks in typography and formatting.</p>
				</li>
				<li>
					<p>Produce ebooks with strict code formatting standards and patterns, so that they can be used as a base for other ebook projects.</p>
				</li>
				<li>
					<p>Produce ebooks with rich semantic data and predictable structure that can be easily machine-processed.</p>
				</li>
				<li>
					<p>Enrich and evangelize the worldwide public domain.</p>
				</li>
			</ol>
		</section>
		<section id="the-short-blurb">
			<h2>The short blurb</h2>
			<p>The Standard Ebooks project is a volunteer driven, not-for-profit effort to produce a collection of high quality, carefully formatted, accessible, open source, and free public domain ebooks that meet or exceed the quality of commercially produced ebooks. The text and cover art in our ebooks is already believed to be in the public domain, and Standard Ebook dedicates its own work to the public domain, thus releasing whole ebooks files themselves into the public domain.</p>
			<h2>How’s this different from Project Gutenberg or other free ebook sites?</h2>
			<p>While there are plenty of places where you can download free and accurately-transcribed public domain ebooks, we feel the <em>quality</em> of those ebooks can often be greatly improved.</p>
			<p>For example, <a href="https://gutenberg.org">Project Gutenberg</a>, a major producer of public-domain ebooks, hosts epub and Kindle files that sometimes lack basic typographic necessities like curly quotes; some of those ebooks are automatically generated and can’t take full advantage of modern ereader technology like popup footnotes or popup tables of contents; they sometimes lack niceties like cover images and title pages; and the quality of individual ebook productions varies greatly.</p>
			<p>Archival sites like the <a href="https://archive.org">Internet Archive</a> (and even Project Gutenberg, to some extent) painstakingly preserve entire texts word-for-word, including original typos and ephemera that are of limited interest to modern readers: everything including centuries-old publishing marks, advertisements for long-vanished publishers, author bios, deeply archaic spellings, and so on. Sometimes all you get is a scan of the actual book pages. That’s great for researchers, archivists, and special-interest readers, but not that great for casual, modern readers.</p>
			<p>The Standard Ebooks project differs from those etext projects in that we aim to make free public domain ebooks that are carefully typeset, cleaned of ancient and irrelevant ephemera, take full advantage of modern ereading technology, are formatted according to a detailed style guide, and that are each held to a standard of quality and internal consistency. Standard Ebooks include carefully chosen cover art based on public domain artwork, and are presented in an attractive way on your ebookshelf. For technically-inclined readers, Standard Ebooks conform to a rigorous coding style, are completely open source, and are <a href="https://github.com/standardebooks/">hosted on Github</a>, so anyone can contribute corrections or improvements easily and directly without having to deal with baroque forums or opaque processes.</p>
		</section>
		<section id="what-makes-se-different">
			<h2>What makes a Standard Ebook different?</h2>
			<ul>
				<li>
					<p><b>Design:</b> Consistent and clean cover art designs that make your ebook look good on the shelf, and attention to layout like section breaks, indentation, and chapter headings.</p>
				</li>
				<li>
					<p><b>Typography:</b> Details like <a href="https://practicaltypography.com/straight-and-curly-quotes.html">curly quotes</a>, <a href="https://practicaltypography.com/ellipses.html">ellipses</a>, <a href="https://practicaltypography.com/hyphens-and-dashes.html">en-, em-, and double-em-dashes</a>, <a href="https://practicaltypography.com/first-line-indents.html">indentation</a>, <a href="https://practicaltypography.com/hyphenation.html">hyphenation</a>, and more make for the smooth reading experience you’d expect from a printed book.</p>
				</li>
				<li>
					<p><b>Ebook best practices:</b> Tables of contents and chapter breaks your ereader can understand, detailed and consistent metadata, popup footnotes, and more. Minimal markup and styling that lets your ereader’s personality shine&mdash;but consistency, so all of our ebooks look familiar.</p>
				</li>
				<li>
					<p><b>Programming best practices:</b> The stuff you can’t see, but that makes for a professional product: semantic markup, minimal and elegant code, smooth build processes, and source control.</p>
				</li>
			</ul>
		</section>
		<section id="se-and-the-public-domain">
			<h2>Standard Ebooks and the public domain</h2>
			<p>All of our ebooks are texts that are thought to be in the public domain in the United States. We base our cover art designs on art that is also thought to be in the public domain in the United States.</p>
			<p>Standard Ebooks puts significant work into designing, formatting, marking up, and hosting our ebooks. While some think we could, or even <em>should</em>, release our work with some kind of copyright notice, instead <strong>Standard Ebooks dedicates the entirety of each of our ebook files, including markup, cover art, and everything in between, to the public domain</strong>.</p>
			<p>The public domain is a priceless resource for all of us, and for the generations after us. It’s a free repository of our culture going back centuries—a way for us to see where we came from and to chart where we’re going. It represents our collective cultural heritage.</p>
			<p>In the past, copyright was a limited boon, designed not to enrich a creator and their children’s children a hundred years from now, but rather to allow a creator to profit by granting a <em>temporary</em> monopoly on reproduction, in exchange for their work to be returned to the public after a few years. Our ancestors—in fact, the framers of the US Constitution—recognized that art builds on art, and that locking up culture benefits a handful but harms the the greater public.</p>
			<p>Today, large corporations are putting a lot of money into twisting our laws to slowly but surely strangle the public domain, making it increasingly remote and inaccessible so they can continue seeking rent on ideas and culture nearly a century old. Today laws lock up work not just for the author’s entire lifetime, but for the lifetime of their children, and <em>their</em> children. Copyright can’t enrich the dead, but it <em>can</em> enrich powerful corporations … at our—at <em>everyone’s</em>—expense.</p>
			<p>Dedicating the work Standard Ebooks produces to the public domain is our small way of letting the world know how important it is to fight that. If corporations have their way, the last liberated and free culture you’ll ever have is what was published before 1924.</p>
			<p>What a sad world that would be.</p>
		</section>
	</article>
</main>
<?= Template::Footer() ?>
