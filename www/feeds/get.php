<?
use function Safe\exec;

$author = HttpInput::Str(GET, 'author');
$collection = HttpInput::Str(GET, 'collection');
$name = null;
$target = null;
$feedTitle = '';
$feedUrl = '';
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
		throw new Exceptions\CollectionNotFoundException();
	}

	$file = WEB_ROOT . '/feeds/opds/' . $name . '/' . $target . '.xml';
	if(!is_file($file)){
		throw new Exceptions\CollectionNotFoundException();
	}

	$label = exec('attr -g se-label ' . escapeshellarg($file)) ?: basename($file, '.xml');

	if($name == 'authors'){
		$title = 'Ebook feeds for ' . $label;
		$description = 'A list of available ebook feeds for ebooks by ' . $label . '.';
		$feedTitle = 'Standard Ebooks - Ebooks by ' . $label;
	}

	if($name == 'collections'){
		$title = 'Ebook feeds for the ' . $label . ' collection';
		$description = 'A list of available ebook feeds for ebooks in the ' . $label . ' collection.';
		$feedTitle = 'Standard Ebooks - Ebooks in the ' . $label . ' collection';
	}

	$feedUrl = '/' . $name . '/' . $target;
}
catch(Exceptions\CollectionNotFoundException){
	Template::Emit404();
}
?><?= Template::Header(['title' => $title, 'feedTitle' => $feedTitle, 'feedUrl' => $feedUrl, 'description' => $description]) ?>
<main>
	<article>
		<h1>Ebook Feeds for <?= Formatter::EscapeHtml($label) ?></h1>
		<?= Template::FeedHowTo() ?>
		<? foreach(Enums\FeedType::cases() as $feedType){ ?>
			<section id="ebooks-by-<?= $feedType->value ?>">
				<h2>
					<? if($feedType == Enums\FeedType::Rss){ ?>
						RSS 2.0
					<? } ?>
					<? if($feedType == Enums\FeedType::Atom){ ?>
						Atom 1.0
					<? } ?>
					<? if($feedType == Enums\FeedType::Opds){ ?>
						OPDS 1.2
					<? } ?>
					Feed
				</h2>
				<? if($feedType == Enums\FeedType::Opds){ ?>
					<p>Import this feed into your ereader app to get access to these ebooks directly in your ereader.</p>
				<? } ?>
				<? if($feedType == Enums\FeedType::Atom){ ?>
					<p>Get updates in your <a href="https://en.wikipedia.org/wiki/Comparison_of_feed_aggregators">RSS client</a> whenever a new ebook is released, or parse this feed for easy scripting.</p>
				<? } ?>
				<? if($feedType == Enums\FeedType::Rss){ ?>
					<p>The predecessor of Atom, compatible with most RSS clients.</p>
				<? } ?>
				<ul class="feed">
					<li>
						<p><a href="/feeds/<?= $feedType->value ?>/<?= $name ?>/<?= $target?>"><?= Formatter::EscapeHtml($label) ?></a></p>
						<p class="url"><? if(isset(Session::$User->Email)){ ?>https://<?= rawurlencode(Session::$User->Email) ?>@<?= SITE_DOMAIN ?><? }else{ ?><?= SITE_URL ?><? } ?>/feeds/<?= $feedType->value ?>/<?= $name ?>/<?= $target?></p>
					</li>
				</ul>
			</section>
		<? } ?>
	</article>
</main>
<?= Template::Footer() ?>
