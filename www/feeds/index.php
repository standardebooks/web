<?
require_once('Core.php');

// A note on mime types:
// `application/rss+xml` is the de jure mime type for RSS feeds.
// But, serving that mime type doesn't display the feed in a browser; rather, it downloads it as an attachment.
// `text/xml` is the de facto RSS mime type, used by most popular feeds and recognized by all RSS readers.
// It also displays the feed in a browser so we can style it with XSLT.
// This is the same for Atom/OPDS (whose de jure mime type is `application/atom+xml`).

?><?= Template::Header(['title' => 'Ebook Feeds', 'description' => 'A list of available feeds of Standard Ebooks ebooks.']) ?>
<main>
	<article>
		<h1>Ebook Feeds</h1>
		<p>We offers several feeds that you can use to get notified about new ebooks, or to browse and download from our catalog directly in your ereader.</p>
		<section id="opds-feeds">
			<h2>OPDS 1.2 feeds</h2>
			<p><a href="https://en.wikipedia.org/wiki/Open_Publication_Distribution_System">OPDS feeds</a> are designed for use with ereading systems like <a href="http://koreader.rocks/">KOreader</a> or <a href="https://calibre-ebook.com">Calibre</a>, or with ereaders like <a href="https://johnfactotum.github.io/foliate/">Foliate</a>. They allow you to search, browse, and download from our catalog, directly in your ereader. They’re also perfect for organizations who wish to download and process our catalog efficiently.</p>
			<ul class="feed">
				<li>
					<p><a href="/feeds/opds">The Standard Ebooks OPDS feed</a></p>
					<p class="url"><?= SITE_URL ?>/feeds/opds</p>
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
		<section id="atom-feeds">
			<h2>Atom 1.0 feeds</h2>
			<p>Atom feeds can be read by one of the many <a href="https://en.wikipedia.org/wiki/Comparison_of_feed_aggregators">RSS clients</a> available for download, like <a href="https://www.thunderbird.net/en-US/">Thunderbird</a>. They contain more information than regular RSS feeds. Most RSS clients can read both Atom and RSS feeds.</p>
			<p>Note that some RSS readers may show these feeds ordered by when an ebook was last updated, even though the feeds are ordered by when an ebook was first released. You should be able to change the sort order in your RSS reader.</p>
			<ul class="feed">
				<li>
					<p><a href="/feeds/atom/new-releases">New releases</a></p>
					<p class="url"><?= SITE_URL ?>/feeds/atom/new-releases</p>
					<p>The thirty latest Standard Ebooks, most-recently-released first.</p>
				</li>
				<li>
					<p><a href="/feeds/atom/all">All ebooks</a></p>
					<p class="url"><?= SITE_URL ?>/feeds/atom/all</p>
					<p>All Standard Ebooks, most-recently-released first.</p>
				</li>
			</ul>
			<section id="atom-ebooks-by-subject">
				<h3>Ebooks by subject</h3>
				<ul class="feed">
					<? foreach(SE_SUBJECTS as $subject){ ?>
					<li>
						<p><a href="/feeds/atom/subjects/<?= Formatter::MakeUrlSafe($subject) ?>"><?= Formatter::ToPlainText($subject) ?></a></p>
						<p class="url"><?= SITE_URL ?>/feeds/atom/subjects/<?= Formatter::MakeUrlSafe($subject) ?></p>
					</li>
					<? } ?>
				</ul>
			</section>
		</section>
		<section id="rss-feeds">
			<h2>RSS 2.0 feeds</h2>
			<p>RSS feeds are an alternative to Atom feeds. They contain less information than Atom feeds, but might be better supported by some RSS readers.</p>
			<ul class="feed">
				<li>
					<p><a href="/feeds/rss/new-releases">New releases</a></p>
					<p class="url"><?= SITE_URL ?>/feeds/rss/new-releases</p>
					<p>The thirty latest Standard Ebooks, most-recently-released first.</p>
				</li>
				<li>
					<p><a href="/feeds/rss/all">All ebooks</a></p>
					<p class="url"><?= SITE_URL ?>/feeds/rss/all</p>
					<p>All Standard Ebooks, most-recently-released first.</p>
				</li>
			</ul>
			<section id="rss-ebooks-by-subject">
				<h3>Ebooks by subject</h3>
				<ul class="feed">
					<? foreach(SE_SUBJECTS as $subject){ ?>
					<li>
						<p><a href="/feeds/rss/subjects/<?= Formatter::MakeUrlSafe($subject) ?>"><?= Formatter::ToPlainText($subject) ?></a></p>
						<p class="url"><?= SITE_URL ?>/feeds/rss/subjects/<?= Formatter::MakeUrlSafe($subject) ?></p>
					</li>
					<? } ?>
				</ul>
			</section>
		</section>
	</article>
</main>
<?= Template::Footer() ?>
