<?
/**
 * GET		/collections/:collection-url-name/feeds
 * GET		/ebooks/:author-url-name/feeds
 */

use function Safe\exec;

try{
	/** @var string $filePath The path for the feed root for this request, passed in from the router. */
	$filePath = $resource['filePath'] ?? throw new Exceptions\CollectionNotFoundException();
	/** @var Enums\FeedCollectionType::Authors|Enums\FeedCollectionType::Collections $collectionType The collection type for this request, passed in from the router. */
	$collectionType = $resource['collectionType'] ?? throw new Exceptions\CollectionNotFoundException();
	$target = basename($filePath, '.xml');
	$label = exec('attr -q -g se-label ' . escapeshellarg($filePath)) ?: basename($filePath, '.xml');

	if($collectionType == Enums\FeedCollectionType::Authors){
		$title = 'Ebook feeds for ' . $label;
		$description = 'A list of available ebook feeds for ebooks by ' . $label . '.';
		$feedTitle = 'Standard Ebooks - Ebooks by ' . $label;
	}

	if($collectionType == Enums\FeedCollectionType::Collections){
		$title = 'Ebook feeds for the ' . $label . ' collection';
		$description = 'A list of available ebook feeds for ebooks in the ' . $label . ' collection.';
		$feedTitle = 'Standard Ebooks - Ebooks in the ' . $label . ' collection';
	}

	$feedUrl = '/' . $collectionType->value . '/' . $target;
}
catch(Exceptions\CollectionNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
?><?= Template::Header(title: $title, feedTitle: $feedTitle, feedUrl: $feedUrl, description: $description) ?>
<main>
	<article>
		<h1>Ebook Feeds for <?= Formatter::EscapeHtml($label) ?></h1>
		<?= Template::FeedHowTo() ?>
		<? foreach(Enums\FeedFormatType::cases() as $feedType){ ?>
			<section id="ebooks-by-<?= $feedType->value ?>">
				<h2><?= $feedType->GetDisplayName() ?> Feed</h2>
				<? if($feedType == Enums\FeedFormatType::Opds){ ?>
					<p>Import this feed into your ereader app to get access to these ebooks directly in your ereader.</p>
				<? } ?>
				<? if($feedType == Enums\FeedFormatType::Atom){ ?>
					<p>Get updates in your <a href="https://en.wikipedia.org/wiki/Comparison_of_feed_aggregators">RSS client</a> whenever a new ebook is released, or parse this feed for easy scripting.</p>
				<? } ?>
				<? if($feedType == Enums\FeedFormatType::Rss){ ?>
					<p>The predecessor of Atom, compatible with most RSS clients.</p>
				<? } ?>
				<ul class="feed">
					<li>
						<p>
							<a href="/feeds/<?= $feedType->value ?>/<?= $collectionType->value ?>/<?= $target?>"><?= Formatter::EscapeHtml($label) ?></a>
						</p>
						<p class="url"><? if(isset(Session::$User->Email)){ ?>https://<?= rawurlencode(Session::$User->Email) ?>@<?= SITE_DOMAIN ?><? }else{ ?><?= SITE_URL ?><? } ?>/feeds/<?= $feedType->value ?>/<?= $collectionType->value ?>/<?= $target?></p>
					</li>
				</ul>
			</section>
		<? } ?>
	</article>
</main>
<?= Template::Footer() ?>
