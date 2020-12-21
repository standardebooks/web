<?
require_once('Core.php');
?><?= Template::Header(['title' => 'What makes Standard Ebooks different', 'highlight' => 'about', 'description' => 'How Standard Ebooks differs from other free ebook projects.']) ?>
<main>
	<article>
		<h1>What makes Standard Ebooks different</h1>
		<ul>
			<li>
				<p><b>Design:</b> Consistent and clean cover art designs that make your ebook look good on the shelf, and attention to layout like section breaks, indentation, and chapter headings.</p>
			</li>
			<li>
				<p><b>Typography:</b> Details like <a href="https://practicaltypography.com/straight-and-curly-quotes.html">curly quotes</a>, <a href="https://practicaltypography.com/ellipses.html">ellipses</a>, <a href="https://practicaltypography.com/hyphens-and-dashes.html">en-, em-, and double-em-dashes</a>, <a href="https://practicaltypography.com/first-line-indents.html">indentation</a>, <a href="https://practicaltypography.com/hyphenation.html">hyphenation</a>, and more make for the smooth reading experience you’d expect from a printed book.</p>
			</li>
			<li>
				<p><b>Ebook best practices:</b> Tables of contents and chapter breaks your ereader can understand, detailed and consistent metadata, popup footnotes, and more. Minimal markup and styling that lets your ereader’s personality shine—but consistency, so all of our ebooks look familiar.</p>
			</li>
			<li>
				<p><b>Programming best practices:</b> The stuff you can’t see, but that makes for a professional product: semantic markup, minimal and elegant code, smooth build processes, and source control.</p>
			</li>
		</ul>
		<section id="the-short-blurb">
			<h2>How’s this different from Project Gutenberg or other free ebook sites?</h2>
			<p>While there are plenty of places where you can download free and accurately-transcribed public domain ebooks, we feel the <em>quality</em> of those ebooks can often be greatly improved.</p>
			<p>For example, <a href="https://gutenberg.org">Project Gutenberg</a>, a major producer of public-domain ebooks, hosts epub and Kindle files that sometimes lack basic typographic necessities like curly quotes; some of those ebooks are automatically generated and can’t take full advantage of modern ereader technology like popup footnotes or popup tables of contents; they sometimes lack niceties like cover images and title pages; and the quality of individual ebook productions varies greatly.</p>
			<p>Archival sites like the <a href="https://archive.org">Internet Archive</a> (and even Project Gutenberg, to some extent) painstakingly preserve entire texts word-for-word, including original typos and ephemera that are of limited interest to modern readers: everything including centuries-old publishing marks, advertisements for long-vanished publishers, author bios, deeply archaic spellings, and so on. Sometimes all you get is a scan of the actual book pages. That’s great for researchers, archivists, and special-interest readers, but not that great for casual, modern readers.</p>
			<p>The Standard Ebooks project differs from those etext projects in that we aim to make free public domain ebooks that are carefully typeset, cleaned of ancient and irrelevant ephemera, take full advantage of modern ereading technology, are formatted according to a detailed style guide, and that are each held to a standard of quality and internal consistency.</p>
			<p>Each Standard Ebook features carefully chosen cover art based on public domain artwork, and is presented in an attractive way in your reading app or device.</p>
			<p>For technically-inclined readers, Standard Ebooks conform to a rigorous coding style, are completely open source, and are <a href="https://github.com/standardebooks/">hosted on GitHub</a>, so anyone can contribute corrections or improvements easily and directly without having to deal with baroque forums or opaque processes.</p>
		</section>
	</article>
</main>
<?= Template::Footer() ?>
