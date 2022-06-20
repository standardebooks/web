<?
require_once('Core.php');

// A note on mime types:
// `application/rss+xml` is the de jure mime type for RSS feeds.
// But, serving that mime type doesn't display the feed in a browser; rather, it downloads it as an attachment.
// `text/xml` is the de facto RSS mime type, used by most popular feeds and recognized by all RSS readers.
// It also displays the feed in a browser so we can style it with XSLT.
// This is the same for OPDS (whose de jure mime type is `application/atom+xml`).

?><?= Template::Header(['description' => 'A list of available feeds of Standard Ebooks ebooks.']) ?>
<main>
	<article>
		<h1>Ebook Feeds</h1>
		<p>We offers several feeds that you can use to get notified about new ebooks, or to browse and download from our catalog directly in your ereader.</p>
		<section id="rss-feeds">
			<h2>RSS feeds</h2>
			<p>Currently there’s only one RSS feed available.</p>
			<ul class="feed">
				<li>
					<p><a href="/rss/new-releases">New releases</a> (RSS 2.0)</p>
					<p class="url"><?= SITE_URL ?>/rss/new-releases</p>
					<p>A list of the thirty latest Standard Ebooks ebook releases, most-recently-released first.</p>
				</li>
			</ul>
		</section>
		<section id="opds-feeds">
			<h2>OPDS feeds</h2>
			<p><a href="https://en.wikipedia.org/wiki/Open_Publication_Distribution_System">OPDS feeds</a> are designed for use with ereading systems like <a href="http://koreader.rocks/">KOreader</a> or <a href="https://calibre-ebook.com">Calibre</a>, or with ereaders like <a href="https://johnfactotum.github.io/foliate/">Foliate</a>. They allow you to search, browse, and download from our catalog, directly in your ereader.</p>
			<ul class="feed">
				<li>
					<p><a href="/opds">The Standard Ebooks OPDS feed</a> (OPDS 1.2)</p>
					<p class="url"><?= SITE_URL ?>/opds</p>
				</li>
			</ul>
			<section>
			<h3>OPDS how-tos and resources</h3>
			<ul>
				<li>
					<p><a href="https://github.com/koreader/koreader/wiki/OPDS-support">Using OPDS with KOreader</a></p>
				</li>
				<li>
					<p><a href="https://github.com/steinarb/opds-reader">OPDS Reader</a>, a plugin that adds OPDS support to Calibre</p>
				</li>
			</ul>
			</section>
		</section>
	</article>
</main>
<?= Template::Footer() ?>
