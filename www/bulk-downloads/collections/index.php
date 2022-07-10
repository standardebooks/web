<?
require_once('Core.php');

use function Safe\apcu_fetch;

$canDownload = false;

if($GLOBALS['User'] !== null && $GLOBALS['User']->Benefits->CanBulkDownload){
	$canDownload = true;
}

$collections = [];

try{
	$collections = apcu_fetch('bulk-downloads-collections');
}
catch(Safe\Exceptions\ApcuException $ex){
	$result = Library::RebuildBulkDownloadsCache();
	$collections = $result['collections'];
}

?><?= Template::Header(['title' => 'Downloads by Collection', 'highlight' => '', 'description' => 'Download zip files containing all of the Standard Ebooks in a given collection.']) ?>
<main>
	<section class="bulk-downloads">
		<h1>Downloads by Collection</h1>
		<? if(!$canDownload){ ?>
			<p><a href="/about#patrons-circle">Patrons circle members</a> can download zip files containing all of the ebooks that were released in a given month of Standard Ebooks history. You can <a href="/donate#patrons-circle">join the Patrons Circle</a> with a small donation in support of our continuing mission to create free, beautiful digital literature.</p>
		<? } ?>
		<p>These zip files contain each ebook in every format we offer, and are updated once daily with the latest versions of each ebook. Read about <a href="/help/how-to-use-our-ebooks#which-file-to-download">which file format to download</a>.</p>
		<?= Template::BulkDownloadTable(['label' => 'Collection', 'collections' => $collections]); ?>
	</section>
</main>
<?= Template::Footer() ?>
