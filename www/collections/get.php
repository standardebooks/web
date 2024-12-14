<?
use function Safe\preg_replace;

try{
	$collection = Collection::GetByUrlName(HttpInput::Str(GET, 'collection'));
	$collectionName = preg_replace('/^The /ius', '', $collection->Name);
	$collectionType = $collection->Type->value ?? 'collection';

	$pageTitle = 'Browse free ebooks in the ' . Formatter::EscapeHtml($collectionName) . ' ' . $collectionType;
	$pageDescription = 'A list of free ebooks in the ' . Formatter::EscapeHtml($collectionName) . ' ' . $collectionType;
	$pageHeader = 'Free Ebooks in the ' . Formatter::EscapeHtml($collectionName) . ' ' . ucfirst($collectionType);

	$feedUrl = '/collections/' . $collection->UrlName;
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

	<?= Template::DonationAlert() ?>

	<p class="ebooks-toolbar">
		<a class="button" href="/collections/<?= Formatter::EscapeHtml($collection->UrlName) ?>/downloads">Download collection</a>
		<a class="button" href="/collections/<?= Formatter::EscapeHtml($collection->UrlName) ?>/feeds">Collection feeds</a>
	</p>

	<? if(sizeof($collection->Ebooks) == 0){ ?>
		<p class="no-results">No ebooks matched your filters.  You can try different filters, or <a href="/ebooks">browse all of our ebooks</a>.</p>
	<? }else{ ?>
		<?= Template::EbookGrid(['ebooks' => $collection->Ebooks, 'view' => Enums\ViewType::Grid, 'collection' => $collection]) ?>
	<? } ?>

	<? if(Session::$User?->Benefits->CanEditCollections){ ?>
		<h2>Metadata</h2>
		<table class="admin-table">
			<tbody>
				<tr>
					<td>Collection ID:</td>
					<td><?= $collection->CollectionId ?></td>
				</tr>
			</tbody>
		</table>
	<? } ?>

	<p class="feeds-alert">We also have <a href="/bulk-downloads">bulk ebook downloads</a> and a <a href="/collections">list of collections</a> available, as well as <a href="/feeds">ebook catalog feeds</a> for use directly in your ereader app or RSS reader.</p>
</main>
<?= Template::Footer() ?>
