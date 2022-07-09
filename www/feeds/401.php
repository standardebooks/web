<?
require_once('Core.php');

use function Safe\preg_match;

$ex = null;

if(isset($_SERVER['PHP_AUTH_USER'])){
	$ex = new Exceptions\InvalidPatronException();
}

$type = '';
preg_match('/^\/feeds\/(opds|rss|atom)/ius', $_SERVER['REQUEST_URI'], $matches);

if(sizeof($matches) > 0){
	$type = $matches[1];
}

$title = 'Standard Ebooks Ebook Feeds';
if($type == 'opds'){
	$title = 'The Standard Ebooks OPDS Feed';
}

if($type == 'rss'){
	$title = 'Standard Ebooks RSS Feeds';
}

if($type == 'atom'){
	$title = 'Standard Ebooks Atom Feeds';
}

?><?= Template::Header(['title' => 'The Standard Ebooks OPDS feed', 'highlight' => '', 'description' => 'Get access to the Standard Ebooks OPDS feed for use in ereading programs in scripting.']) ?>
<main>
	<section class="narrow has-hero">
		<? if($type == 'opds'){ ?>
			<h1>The Standard Ebooks OPDS Feed</h1>
		<? }elseif($type == 'rss'){ ?>
			<h1>Standard Ebooks RSS Feeds</h1>
		<? }elseif($type == 'atom'){ ?>
			<h1>Standard Ebooks Atom Feeds</h1>
		<? }else{ ?>
			<h1>Standard Ebooks Ebook Feeds</h1>
		<? } ?>
		<picture>
			<source srcset="/images/new-york-daily-news@2x.avif 2x, /images/new-york-daily-news.avif 1x" type="image/avif"/>
			<source srcset="/images/new-york-daily-news@2x.jpg 2x, /images/new-york-daily-news.jpg 1x" type="image/jpg"/>
			<img src="/images/new-york-daily-news@2x.jpg" alt="A mug next to a pipe and a newspaper."/>
		</picture>
		<? if($ex !== null){ ?>
		<ul class="message error">
			<li>
				<p><?= Formatter::ToPlainText($ex->getMessage()) ?></p>
			</li>
		</ul>
		<? } ?>
		<? if($type == 'opds'){ ?>
			<p><a href="https://en.wikipedia.org/wiki/Open_Publication_Distribution_System">OPDS feeds</a>, or “catalogs,” can be added to ereading apps on phones and tablets to search, browse, and download from our entire catalog, directly in your ereader. Most modern ereading apps support OPDS catalogs.</p>
			<p>They’re also perfect for scripting, or for libraries or other organizations who wish to download and process our catalog of ebooks.</p>
		<? }elseif($type == 'rss'){ ?>
			<p>RSS feeds are the predecessors of <a href="/feeds/atom">Atom feeds</a>. They contain less information than Atom feeds, but might be better supported by some news readers.</p>
		<? }elseif($type == 'atom'){ ?>
			<p>Atom feeds can be read by one of the many <a href="https://en.wikipedia.org/wiki/Comparison_of_feed_aggregators">RSS clients</a> available for download, like <a href="https://www.thunderbird.net/en-US/">Thunderbird</a>. They contain more information than regular <a href="/feeds/rss">RSS feeds</a>. Most RSS clients can read both Atom and RSS feeds.</p>
			<p>Note that some RSS readers may show these feeds ordered by when an ebook was last updated, even though the feeds are ordered by when an ebook was first released. You should be able to change the sort order in your RSS reader.</p>
		<? } ?>
		<?= Template::FeedHowTo() ?>
	</section>
</main>
<?= Template::Footer() ?>
