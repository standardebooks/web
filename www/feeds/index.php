<?
require_once('Core.php');

?><?= Template::Header(['title' => 'Ebook Feeds', 'description' => 'A list of available feeds of Standard Ebooks ebooks.']) ?>
<main>
	<section class="narrow has-hero">
		<h1>Ebook Feeds</h1>
		<picture data-caption="Rack Pictures for Dr. Nones. William A. Mitchell, 1879">
			<source srcset="/images/rack-picture-for-dr-nones@2x.avif 2x, /images/rack-picture-for-dr-nones.avif 1x" type="image/avif"/>
			<source srcset="/images/rack-picture-for-dr-nones@2x.jpg 2x, /images/rack-picture-for-dr-nones.jpg 1x" type="image/jpg"/>
			<img src="/images/rack-picture-for-dr-nones@2x.jpg" alt="Postal mail attached to a billboard."/>
		</picture>
		<p>We offer several ebook feeds that you can use in your ereading app to browse, search, and download from our catalog. You can also add our feeds to your RSS client to get notified of new ebooks as they’re released, or to browse our catalog from your news reader.</p>
		<?= Template::FeedHowTo() ?>
		<section id="opds-feeds">
			<h2>OPDS 1.2 feeds</h2>
			<p><a href="https://en.wikipedia.org/wiki/Open_Publication_Distribution_System">OPDS feeds</a>, or “catalogs,” can be added to ereading apps on phones and tablets to search, browse, and download from our ebook catalog, directly in your ereader. Most modern ereading apps support OPDS catalogs.</p>
			<p>They’re also perfect for scripting, or for libraries or other organizations who wish to download, process, and keep up to date with our catalog of ebooks.</p>
			<p>To connect your ereading app to our catalog, enter the URL below when prompted by your app:</p>
			<ul class="feed">
				<li>
					<p><a href="/feeds/opds">The Standard Ebooks OPDS feed</a></p>
					<p class="url"><? if($GLOBALS['User'] !== null){ ?>https://<?= rawurlencode($GLOBALS['User']->Email) ?>@<?= SITE_DOMAIN ?><? }else{ ?><?= SITE_URL ?><? } ?>/feeds/opds</p>
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
