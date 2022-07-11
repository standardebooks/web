<?
require_once('Core.php');

use function Safe\apcu_fetch;
use function Safe\preg_replace;

$canDownload = false;
$name = HttpInput::Str(GET, 'name', false) ?? '';

if($name != 'authors' && $name != 'collections' && $name != 'subjects' && $name != 'months'){
	$name = 'subjects';
}

if($GLOBALS['User'] !== null && $GLOBALS['User']->Benefits->CanBulkDownload){
	$canDownload = true;
}

$collection = [];

try{
	$collection = apcu_fetch('bulk-downloads-' . $name);
}
catch(Safe\Exceptions\ApcuException $ex){
	$result = Library::RebuildBulkDownloadsCache();
	$collection = $result[$name];
}

$title = preg_replace('/s$/', '', ucfirst($name));

?><?= Template::Header(['title' => 'Downloads by ' . $title, 'highlight' => '', 'description' => 'Download zip files containing all of the Standard Ebooks in a given collection.']) ?>
<main>
	<section class="bulk-downloads">
		<h1>Down­loads by <?= $title ?></h1>
		<? if(!$canDownload){ ?>
			<p><a href="/about#patrons-circle">Patrons circle members</a> get convenient access to zip files containing collections of different categories of ebooks. You can <a href="/donate#patrons-circle">join the Patrons Circle</a> with a small donation in support of our continuing mission to create free, beautiful digital literature, and download these collections files too.</p>
		<? } ?>
		<p>These zip files contain each ebook in every format we offer, and are updated once daily with the latest versions of each ebook. Read about <a href="/help/how-to-use-our-ebooks#which-file-to-download">which file format to download</a>.</p>
		<? if($name == 'months'){ ?>
			<table class="download-list">
				<tbody>
			<? foreach($collection as $year => $months){
				$yearHeader = Formatter::ToPlainText($year);
			 ?>
				<tr class="year-header">
					<th colspan="13" scope="colgroup" id="<?= $yearHeader ?>"><?= Formatter::ToPlainText((string)$year) ?></th>
				</tr>
				<tr class="mid-header">
					<th id="<?= $yearHeader?>-type" scope="col">Month</th>
					<th id="<?= $yearHeader ?>-ebooks" scope="col">Ebooks</th>
					<th id="<?= $yearHeader ?>-updated" scope="col">Updated</th>
					<th id="<?= $yearHeader ?>-download" colspan="10" scope="col">Ebook format</th>
				</tr>

				<? foreach($months as $month => $collection){
					$monthHeader = Formatter::ToPlainText($month);
				?>
				<tr>
					<th class="row-header" headers="<?= $yearHeader ?> <?= $monthHeader ?> <?= $yearHeader ?>-type" id="<?= $monthHeader ?>"><?= Formatter::ToPlainText($month) ?></th>
					<td class="number" headers="<?= $yearHeader ?> <?= $monthHeader ?> <?= $yearHeader ?>-ebooks"><?= Formatter::ToPlainText(number_format($collection->EbookCount)) ?></td>
					<td class="number" headers="<?= $yearHeader ?> <?= $monthHeader ?> <?= $yearHeader ?>-updated"><?= Formatter::ToPlainText($collection->UpdatedString) ?></td>

					<? foreach($collection->ZipFiles as $item){ ?>
						<td headers="<?= $yearHeader ?> <?= $monthHeader ?> <?= $yearHeader ?>-download" class="download"><a href="<?= $item->Url ?>"><?= $item->Type ?></a></td>
						<td headers="<?= $yearHeader ?> <?= $monthHeader ?> <?= $yearHeader ?>-download">(<?= Formatter::ToPlainText($item->Size) ?>)</td>
					<? } ?>
				</tr>
				<? } ?>

			<? } ?>
				</tbody>
			</table>
		<? }else{ ?>
		<?= Template::BulkDownloadTable(['label' => $title, 'collections' => $collection]); ?>
		<? } ?>
	</section>
</main>
<?= Template::Footer() ?>
