<?
use function Safe\preg_replace;

try{
	$collection = HttpInput::Str(GET, 'collection') ?? '';
	$collectionObject = null;
	$collectionName = '';
	$collectionType = '';

	$ebooks = Library::GetEbooksByCollection($collection);
	// Get the *actual* name of the collection, in case there are accent marks (like "Arsène Lupin")
	if(sizeof($ebooks) > 0){
		foreach($ebooks[0]->CollectionMemberships as $cm){
			$c = $cm->Collection;
			if($collection == Formatter::MakeUrlSafe($c->Name)){
				$collectionObject = $c;
			}
		}
	}

	if($collectionObject === null){
		throw new Exceptions\CollectionNotFoundException();
	}

	$collectionName = preg_replace('/^The /ius', '', $collectionObject->Name);
	$collectionType = $collectionObject->Type ?? 'collection';

	$pageTitle = 'Browse free ebooks in the ' . Formatter::EscapeHtml($collectionName) . ' ' . $collectionType;
	$pageDescription = 'A list of free ebooks in the ' . Formatter::EscapeHtml($collectionName) . ' ' . $collectionType;
	$pageHeader = 'Free Ebooks in the ' . Formatter::EscapeHtml($collectionName) . ' ' . ucfirst($collectionType);

	$feedUrl = '/collections/' . $collection;
	$feedTitle = 'Standard Ebooks - Ebooks in the ' . Formatter::EscapeHtml($collectionName) . ' ' . $collectionType;
}
catch(Exceptions\CollectionNotFoundException){
	Template::Emit404();
}
?><?= Template::Header(['title' => $pageTitle, 'feedUrl' => $feedUrl, 'feedTitle' => $feedTitle, 'highlight' => 'ebooks', 'description' => $pageDescription]) ?>
<main class="ebooks">
	<h1 class="is-collection"><?= $pageHeader ?></h1>
	<?= Template::DonationCounter() ?>
	<?= Template::DonationProgress() ?>
	<? if(!DONATION_DRIVE_ON && !DONATION_DRIVE_COUNTER_ON && DONATION_HOLIDAY_ALERT_ON){ ?>
	<?= Template::DonationAlert() ?>
	<? } ?>
	<p class="ebooks-toolbar">
		<a class="button" href="/collections/<?= Formatter::EscapeHtml($collection) ?>/downloads">Download collection</a>
		<a class="button" href="/collections/<?= Formatter::EscapeHtml($collection) ?>/feeds">Collection feeds</a>
	</p>
	<? if(sizeof($ebooks) == 0){ ?>
		<p class="no-results">No ebooks matched your filters.  You can try different filters, or <a href="/ebooks">browse all of our ebooks</a>.</p>
	<? }else{ ?>
		<?= Template::EbookGrid(['ebooks' => $ebooks, 'view' => ViewType::Grid, 'collection' => $collectionObject]) ?>
	<? } ?>

	<p class="feeds-alert">We also have <a href="/bulk-downloads">bulk ebook downloads</a> and a <a href="/collections">list of collections</a> available, as well as <a href="/feeds">ebook catalog feeds</a> for use directly in your ereader app or RSS reader.</p>
</main>
<?= Template::Footer() ?>
