<?
require_once('Core.php');
?><?= Template::Header(['title' => 'Report Errors', 'highlight' => 'contribute', 'description' => 'How to report a typo or error you’ve found in a Standard Ebooks ebook.']) ?>
<main>
	<article>
		<h1>Report Errors</h1>
		<p>You can report any kind of error in a Standard Ebook to the Standard Ebooks project and we’ll do our best to get it corrected as soon as we can.</p>
		<p>If you’re a software developer or you’re comfortable with technology, you can fix the error yourself at the ebook’s <a href="https://www.github.com/standardebooks/">GitHub repository</a>, and submit a pull request.</p>
		<p>If you’d rather not do that, then you can report an error directly to us and we’ll take care of it. To report an error, you’ll need:</p>
		<ol>
			<li>
				<p><b>The author and title of the ebook in question.</b> Even better is a direct link to the Standard Ebooks page for that ebook. You can find that link in the ebook’s colophon, which is accessible via the table of contents and is always found at the end of the ebook.</p>
			</li>
			<li>
				<p><b>The word immediately before and after the word that contains the error.</b> Usually three words is enough for us to quickly locate the error in the ebook, but if you think only three words isn’t enough, a few more won’t hurt.</p>
				<p>If the error doesn’t include a word, just send us a quick description of what’s going on.</p>
				<p>For example, say you’ve spotted the incorrectly curled quotation mark in the text below:</p>
				<blockquote>
					<p>He rose excitedly. “Go get <mark class="error">‘</mark>em, tiger!” he cried.</p>
				</blockquote>
				<p>All you’d have to send us is:</p>
				<blockquote>
					<p>get ‘em, tiger</p>
				</blockquote>
				<p>And we’ll be able to find the section you’re talking about and correctly curl the quotation mark.</p>
			</li>
		</ol>
		<p>Once you have these two items, <a href="https://groups.google.com/forum/#!forum/standardebooks">contact us via our mailing list</a> with that information and we’ll get right on it!</p>
	</article>
</main>
<?= Template::Footer() ?>
