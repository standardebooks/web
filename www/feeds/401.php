<?
require_once('Core.php');

use function Safe\preg_match;

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
	<section class="narrow">
		<? if($type == 'opds'){ ?>
			<h1>The Standard Ebooks OPDS Feed</h1>
			<p><a href="https://en.wikipedia.org/wiki/Open_Publication_Distribution_System">OPDS feeds</a> are designed for use with ereading apps on your phone or tablet, or with ereading systems like <a href="http://koreader.rocks/">KOreader</a>. Add our OPDS feed to your ereading app to search, browse, and download from our entire catalog, directly in your ereader.</p>
			<p>They’re also perfect for scripting, or for libraries or other organizations who wish to download and process our catalog of ebooks.</p>
		<? }elseif($type == 'rss'){ ?>
			<h1>Standard Ebooks RSS Feeds</h1>
			<p>RSS feeds are an alternative to <a href="/feeds/atom">Atom feeds</a>. They contain less information than Atom feeds, but might be better supported by some RSS readers.</p>
		<? }elseif($type == 'atom'){ ?>
			<h1>Standard Ebooks Atom Feeds</h1>
			<p>Atom feeds can be read by one of the many <a href="https://en.wikipedia.org/wiki/Comparison_of_feed_aggregators">RSS clients</a> available for download, like <a href="https://www.thunderbird.net/en-US/">Thunderbird</a>. They contain more information than regular RSS feeds. Most RSS clients can read both Atom and RSS feeds.</p>
			<p>Note that some RSS readers may show these feeds ordered by when an ebook was last updated, even though the feeds are ordered by when an ebook was first released. You should be able to change the sort order in your RSS reader.</p>
		<? }else{ ?>
			<h1>Standard Ebooks Ebook Feeds</h1>
		<? } ?>
		<?= Template::FeedHowTo() ?>
	</section>
</main>
<?= Template::Footer() ?>
