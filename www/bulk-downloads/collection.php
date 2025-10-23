<?
use function Safe\preg_replace;

$canDownload = false;
$class = HttpInput::Str(GET, 'class') ?? '';

try{
	$labelType = Enums\BulkDownloadLabelType::from($class);
}
catch(ValueError){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}

if(Session::$User?->Benefits->CanBulkDownload){
	$canDownload = true;
}

if($labelType == Enums\BulkDownloadLabelType::Month){
	$bulkDownloadCollections = BulkDownloadCollection::GetAllByMonthLabelType();
}
else{
	$bulkDownloadCollections = BulkDownloadCollection::GetAllByLabelType($labelType);
}

$title = preg_replace('/s$/', '', ucfirst($labelType->value));

?><?= Template::Header(title: 'Downloads by ' . $title, description: 'Download zip files containing all of the Standard Ebooks in a given collection.') ?>
<main>
	<section class="bulk-downloads">
		<h1>Down­loads by <?= $title ?></h1>
		<? if(!$canDownload){ ?>
			<p><a href="/about#patrons-circle">Patrons circle members</a> get convenient access to zip files containing collections of different categories of ebooks. You can <a href="/donate#patrons-circle">join the Patrons Circle</a> with a small donation in support of our continuing mission to create free, beautiful digital literature, and download these collections files too.</p>
		<? } ?>
		<p>These zip files contain each ebook in every format we offer, and are kept updated with the latest versions of each ebook. Read about <a href="/help/how-to-use-our-ebooks#which-file-to-download">which file format to download</a>.</p>
		<? if($labelType == Enums\BulkDownloadLabelType::Month){ ?>
			<table class="data-table">
				<caption aria-hidden="true">Scroll right →</caption>
				<tbody>
					<? foreach($bulkDownloadCollections as $year => $months){ ?>
						<? $yearHeader = Formatter::EscapeHtml($year); ?>
						<tr class="year-header">
							<th colspan="13" scope="colgroup" id="<?= $yearHeader ?>">
								<?= Formatter::EscapeHtml((string)$year) ?>
							</th>
						</tr>
						<tr class="mid-header">
							<th id="<?= $yearHeader?>-type" scope="col">Month</th>
							<th id="<?= $yearHeader ?>-ebooks" scope="col">Ebooks</th>
							<th id="<?= $yearHeader ?>-updated" scope="col">Updated</th>
							<th id="<?= $yearHeader ?>-download" colspan="10" scope="col">Ebook format</th>
						</tr>

						<? foreach($months as $month => $bdc){ ?>
							<? $monthHeader = Formatter::EscapeHtml($month); ?>
							<tr>
								<th class="row-header" headers="<?= $yearHeader ?> <?= $monthHeader ?> <?= $yearHeader ?>-type" id="<?= $monthHeader ?>">
									<?= Formatter::EscapeHtml($month) ?>
								</th>
								<td class="number" headers="<?= $yearHeader ?> <?= $monthHeader ?> <?= $yearHeader ?>-ebooks">
									<?= Formatter::EscapeHtml(number_format($bdc->EbookCount)) ?>
								</td>
								<td class="number" headers="<?= $yearHeader ?> <?= $monthHeader ?> <?= $yearHeader ?>-updated">
									<?= Formatter::EscapeHtml($bdc->UpdatedString) ?>
								</td>

								<? foreach($bdc->ZipFiles as $zipFile){ ?>
									<td headers="<?= $yearHeader ?> <?= $monthHeader ?> <?= $yearHeader ?>-download" class="download">
										<a href="<?= $zipFile->DownloadUrl ?>"><?= $zipFile->Format->Display() ?></a>
									</td>
									<td headers="<?= $yearHeader ?> <?= $monthHeader ?> <?= $yearHeader ?>-download">
										(<?= Formatter::EscapeHtml($zipFile->DownloadFileSizeFormatted) ?>)
									</td>
								<? } ?>
							</tr>
						<? } ?>
					<? } ?>
				</tbody>
			</table>
		<? }else{ ?>
			<?= Template::BulkDownloadTable(label: $title, bulkDownloadCollections: $bulkDownloadCollections); ?>
		<? } ?>
	</section>
</main>
<?= Template::Footer() ?>
