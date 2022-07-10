<?
require_once('Core.php');

use function Safe\apcu_fetch;

$bulkDownloadCollection = null;
$exception = null;
$user = null;

try{
	if(isset($_SERVER['PHP_AUTH_USER'])){
		$user = User::GetByPatronIdentifier($_SERVER['PHP_AUTH_USER']);
	}
}
catch(Exceptions\InvalidUserException $ex){
	$exception = new Exceptions\InvalidPatronException();
}

try{

	$collection = HttpInput::Str(GET, 'collection', false) ?? '';
	$collections = [];

	try{
		$collections = apcu_fetch('bulk-downloads-collections');
	}
	catch(Safe\Exceptions\ApcuException $ex){
		$result = Library::RebuildBulkDownloadsCache();
		$collections = $result['collections'];
	}

	if(!isset($collections[$collection]) || sizeof($collections[$collection]) == 0){
		throw new Exceptions\InvalidCollectionException();
	}

	$bulkDownloadCollection = $collections[$collection];
}
catch(Exceptions\InvalidCollectionException $ex){
	Template::Emit404();
}

?><?= Template::Header(['title' => 'Download ', 'highlight' => '', 'description' => 'Download zip files containing all of the Standard Ebooks released in a given month.']) ?>
<main>
	<section class="bulk-downloads">
		<h1>Download the <?= $bulkDownloadCollection[0]->Label ?> Collection</h1>
		<?= Template::Error(['exception' => $exception]) ?>
		<? if($user === null){ ?>
			<p><a href="/about#patrons-circle">Patrons circle members</a> can download zip files containing all of the ebooks in a collection. You can <a href="/donate#patrons-circle">join the Patrons Circle</a> with a small donation in support of our continuing mission to create free, beautiful digital literature.</p>
			<p>If you’re a Patrons Circle member, when prompted enter your email address and leave the password field blank to download this collection.</p>
		<? }else{ ?>
			<p>Select the ebook format in which you’d like to download this collection.</p>
			<p>You can also read about <a href="/help/how-to-use-our-ebooks#which-file-to-download">which ebook format to download</a>.</p>
		<? } ?>
		<?= Template::BulkDownloadTable(['label' => 'Collection', 'collections' => [$bulkDownloadCollection]]); ?>
	</section>
</main>
<?= Template::Footer() ?>
