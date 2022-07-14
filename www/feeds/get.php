<?
require_once('Core.php');

$author = HttpInput::Str(GET, 'author', false);
$collection = HttpInput::Str(GET, 'collection', false);
$name = null;
$target = null;
$feedTypes = ['opds', 'atom', 'rss'];
$title = '';
$description = '';
$label = null;

if($author !== null){
	$name = 'authors';
	$target = $author;
}

if($collection !== null){
	$name = 'collections';
	$target = $collection;
}

try{
	if($target === null || $name === null){
		throw new Exceptions\InvalidCollectionException();
	}

	$file = WEB_ROOT . '/feeds/opds/' . $name . '/' . $target . '.xml';
	if(!is_file($file)){
		throw new Exceptions\InvalidCollectionException();
	}

	$label = exec('attr -g se-label ' . escapeshellarg($file)) ?: basename($file, '.xml');

	if($name == 'authors'){
		$title = 'Ebook feeds for ' . $label;
		$description = 'A list of available ebook feeds for ebooks by ' . $label . '.';
	}

	if($name == 'collections'){
		$title = 'Ebook feeds for the ' . $label . ' collection';
		$description = 'A list of available ebook feeds for ebooks in the ' . $label . ' collection.';
	}
}
catch(Exceptions\InvalidCollectionException $ex){
	Template::Emit404();
}
?><?= Template::Header(['title' => $title, 'description' => $description]) ?>
<main>
	<article>
		<h1>Ebook Feeds for <?= Formatter::ToPlainText($label) ?></h1>
		<?= Template::FeedHowTo() ?>
		<? foreach($feedTypes as $type){ ?>
		<section id="ebooks-by-<?= $type ?>">
			<h2><? if($type == 'rss'){ ?>RSS 2.0<? } ?><? if($type == 'atom'){ ?>Atom 1.0<? } ?><? if($type == 'opds'){ ?>OPDS 1.2<? } ?> Feed</h2>
			<? if($type == 'opds'){ ?>
			<p>Import this feed into your ereader app to get access to these ebooks directly in your ereader.</p>
			<? } ?>
			<? if($type == 'atom'){ ?>
			<p>Get updates in your <a href="https://en.wikipedia.org/wiki/Comparison_of_feed_aggregators">RSS client</a> whenever a new ebook is released, or parse this feed for easy scripting.</p>
			<? } ?>
			<? if($type == 'rss'){ ?>
			<p>The predecessor of Atom, compatible with most RSS clients.</p>
			<? } ?>
			<ul class="feed">
				<li>
					<p><a href="/feeds/<?= $type ?>/<?= $name ?>/<?= $target?>"><?= Formatter::ToPlainText($label) ?></a></p>
					<p class="url"><? if($GLOBALS['User'] !== null){ ?>https://<?= rawurlencode($GLOBALS['User']->Email) ?>@<?= SITE_DOMAIN ?><? }else{ ?><?= SITE_URL ?><? } ?>/feeds/<?= $type ?>/<?= $name ?>/<?= $target?></p>
				</li>
			</ul>
		</section>
		<? } ?>
	</article>
</main>
<?= Template::Footer() ?>
