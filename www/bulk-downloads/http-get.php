<?
/**
 * GET		/ebooks/:author-url-name/downloads
 * GET		/collections/:collection-url-name/downloads
 */

try{
	/** @var BulkDownloadCollection $bulkDownloadCollection The `BulkDownloadCollection` for this request, passed in from the router. */
	$bulkDownloadCollection = $resource ?? throw new Exceptions\BulkDownloadCollectionNotFoundException();
}
catch(Exceptions\BulkDownloadCollectionNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
?><?= Template::Header(title: 'Download the ' . $bulkDownloadCollection->LabelName . ' Collection', description: 'Download zip files containing all of the Standard Ebooks in the ' . $bulkDownloadCollection->LabelName . ' collection.') ?>
<main>
	<section class="bulk-downloads">
		<h1>Download the <?= Formatter::EscapeHtml($bulkDownloadCollection->LabelName) ?> Collection</h1>
		<? if(Session::$User?->Benefits->CanBulkDownload){ ?>
			<p>Select the ebook format in which you’d like to download this collection.</p>
			<p>You can also read about <a href="/help/how-to-use-our-ebooks#which-file-to-download">which ebook format to download</a>.</p>
		<? }else{ ?>
			<p><a href="/about#patrons-circle">Patrons circle members</a> can download zip files containing all of the ebooks in a collection. You can <a href="/donate#patrons-circle">join the Patrons Circle</a> with a small donation in support of our continuing mission to create free, beautiful digital literature.</p>
		<? } ?>
		<?= Template::BulkDownloadTable(label: 'Collection', bulkDownloadCollections: [$bulkDownloadCollection]); ?>
	</section>
</main>
<?= Template::Footer() ?>
