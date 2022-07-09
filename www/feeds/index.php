<?
require_once('Core.php');

?><?= Template::Header(['title' => 'Ebook Feeds', 'description' => 'A list of available feeds of Standard Ebooks ebooks.']) ?>
<main>
	<section class="narrow has-hero">
		<h1>Ebook Feeds</h1>
		<picture>
			<source srcset="/images/new-york-daily-news@2x.avif 2x, /images/new-york-daily-news.avif 1x" type="image/avif"/>
			<source srcset="/images/new-york-daily-news@2x.jpg 2x, /images/new-york-daily-news.jpg 1x" type="image/jpg"/>
			<img src="/images/new-york-daily-news@2x.jpg" alt="A mug next to a pipe and a newspaper."/>
		</picture>
		<p>We offer several ebook feeds that you can use in your ereading app to browse, search, and download from our catalog. You can also add our feeds to your RSS client to get notified of new ebooks as they’re released, or to browse our catalog from your news reader.</p>
		<?= Template::FeedHowTo() ?>
		<section id="opds-feeds">
			<h2>OPDS 1.2 feeds</h2>
			<p><a href="https://en.wikipedia.org/wiki/Open_Publication_Distribution_System">OPDS feeds</a>, or “catalogs,” can be added to ereading apps on phones and tablets, or to ereading systems like <a href="http://koreader.rocks/">KOreader</a>. Add our OPDS feed to your ereading app to search, browse, and download from our entire catalog, directly in your ereader.</p>
			<p>They’re also perfect for scripting, or for libraries or other organizations who wish to download and process our catalog of ebooks.</p>
			<ul class="feed">
				<li>
					<p><a href="/feeds/opds">The Standard Ebooks OPDS feed</a></p>
					<p class="url"><?= SITE_URL ?>/feeds/opds</p>
				</li>
			</ul>
		</section>
		<section id="atom-feeds">
			<h2>Atom 1.0 feeds</h2>
			<p>Atom feeds can be read by one of the many <a href="https://en.wikipedia.org/wiki/Comparison_of_feed_aggregators">RSS clients</a> available for download, like <a href="https://www.thunderbird.net/en-US/">Thunderbird</a>. They contain more information than regular <a href="/feeds/rss">RSS feeds</a>. Most RSS clients can read both Atom and RSS feeds.</p>
			<p>Note that some RSS readers may show these feeds ordered by when an ebook was last updated, even though the feeds are ordered by when an ebook was first released. You should be able to change the sort order in your RSS reader.</p>
			<ul class="feed">
				<li><p><a href="/feeds/atom">Standard Ebooks Atom feeds</a></p></li>
			</ul>
		</section>
		<section id="rss-feeds">
			<h2>RSS 2.0 feeds</h2>
			<p>RSS feeds are the predecessors of <a href="/feeds/atom">Atom feeds</a>. They contain less information than Atom feeds, but might be better supported by some news readers.</p>
			<ul class="feed">
				<li><p><a href="/feeds/rss">Standard Ebooks RSS feeds</a></p></li>
			</ul>
		</section>
	</section>
</main>
<?= Template::Footer() ?>
