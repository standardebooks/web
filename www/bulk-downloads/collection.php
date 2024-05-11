<?
use function Safe\apcu_fetch;
use function Safe\preg_replace;

$canDownload = false;
$class = HttpInput::Str(HttpVariableSource::Get, 'class');

if($class === null || ($class != 'authors' && $class != 'collections' && $class != 'subjects' && $class != 'months')){
	Template::Emit404();
}

if($GLOBALS['User'] !== null && $GLOBALS['User']->Benefits->CanBulkDownload){
	$canDownload = true;
}

$collection = [];

try{
	$collection = apcu_fetch('bulk-downloads-' . $class);
}
catch(Safe\Exceptions\ApcuException){
	$result = Library::RebuildBulkDownloadsCache();
	$collection = $result[$class];
}

$title = preg_replace('/s$/', '', ucfirst($class));

?><?= Template::Header(['title' => 'Downloads by ' . $title, 'highlight' => '', 'description' => 'Download zip files containing all of the Standard Ebooks in a given collection.']) ?>
<main>
	<section class="bulk-downloads">
		<h1>Down­loads by <?= $title ?></h1>
		<? if(!$canDownload){ ?>
			<p><a href="/about#patrons-circle">Patrons circle members</a> get convenient access to zip files containing collections of different categories of ebooks. You can <a href="/donate#patrons-circle">join the Patrons Circle</a> with a small donation in support of our continuing mission to create free, beautiful digital literature, and download these collections files too.</p>
		<? } ?>
		<p>These zip files contain each ebook in every format we offer, and are kept updated with the latest versions of each ebook. Read about <a href="/help/how-to-use-our-ebooks#which-file-to-download">which file format to download</a>.</p>
		<? if($class == 'months'){ ?>
			<table class="download-list">
				<caption aria-hidden="hidden">Scroll right →</caption>
				<tbody>
			<? foreach($collection as $year => $months){
				$yearHeader = Formatter::EscapeHtml($year);
			 ?>
				<tr class="year-header">
					<th colspan="13" scope="colgroup" id="<?= $yearHeader ?>"><?= Formatter::EscapeHtml((string)$year) ?></th>
				</tr>
				<tr class="mid-header">
					<th id="<?= $yearHeader?>-type" scope="col">Month</th>
					<th id="<?= $yearHeader ?>-ebooks" scope="col">Ebooks</th>
					<th id="<?= $yearHeader ?>-updated" scope="col">Updated</th>
					<th id="<?= $yearHeader ?>-download" colspan="10" scope="col">Ebook format</th>
				</tr>

				<? foreach($months as $month => $collection){
					$monthHeader = Formatter::EscapeHtml($month);
				?>
				<tr>
					<th class="row-header" headers="<?= $yearHeader ?> <?= $monthHeader ?> <?= $yearHeader ?>-type" id="<?= $monthHeader ?>"><?= Formatter::EscapeHtml($month) ?></th>
					<td class="number" headers="<?= $yearHeader ?> <?= $monthHeader ?> <?= $yearHeader ?>-ebooks"><?= Formatter::EscapeHtml(number_format($collection->EbookCount)) ?></td>
					<td class="number" headers="<?= $yearHeader ?> <?= $monthHeader ?> <?= $yearHeader ?>-updated"><?= Formatter::EscapeHtml($collection->UpdatedString) ?></td>

					<? foreach($collection->ZipFiles as $item){ ?>
						<td headers="<?= $yearHeader ?> <?= $monthHeader ?> <?= $yearHeader ?>-download" class="download"><a href="<?= $item->Url ?>"><?= $item->Type ?></a></td>
						<td headers="<?= $yearHeader ?> <?= $monthHeader ?> <?= $yearHeader ?>-download">(<?= Formatter::EscapeHtml($item->Size) ?>)</td>
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
