<?
require_once('Core.php');
?><?= Template::Header(['description' => 'A list of available RSS feeds of Standard Ebooks ebooks.']) ?>
<main>
	<article>
		<h1>RSS Feeds</h1>
		<p>Currently there is only one RSS feed available:</p>
		<ul>
			<li>
				<p><a href="/rss/new-releases">New releases</a>: A list of the 30 latest Standard Ebooks ebook releases, most-recently-released first.</p>
			</li>
		</ul>

		<p>You may also be interested in our OPDS feed, which is designed for use with ebook libraries like Calibre:</p>
		<ul>
			<li>
				<p><a href="/opds/">The Standard Ebooks OPDS feed</a></p>
			</li>
		</ul>
	</article>
</main>
<?= Template::Footer() ?>
