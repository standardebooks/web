<?
/**
 * @var string $label
 * @var array<BulkDownloadCollection> $bulkDownloadCollections
 */
?>
<table class="data-table bulk-downloads-table">
	<caption aria-hidden="true">Scroll right →</caption>
	<thead>
		<tr class="mid-header">
			<th scope="col"><?= Formatter::EscapeHtml($label) ?></th>
			<th scope="col">Ebooks</th>
			<th scope="col">Updated</th>
			<th scope="col" colspan="10">Ebook format</th>
		</tr>
	</thead>
	<tbody>
		<? foreach($bulkDownloadCollections as $bdc){ ?>
			<tr>
				<td class="row-header"><a href="<?= $bdc->LabelUrl ?>"><?= Formatter::EscapeHtml($bdc->LabelName) ?></a></td>
				<td class="number">
					<?= Formatter::EscapeHtml(number_format($bdc->EbookCount)) ?>
				</td>
				<td class="number">
					<?= Formatter::EscapeHtml($bdc->UpdatedString) ?>
				</td>
				<? foreach($bdc->ZipFiles as $zipFile){ ?>
					<td class="download">
						<a href="<?= $zipFile->DownloadUrl ?>"><?= $zipFile->Format->Display() ?></a>
					</td>
					<td>
						(<?= Formatter::EscapeHtml($zipFile->DownloadFileSizeFormatted) ?>)
					</td>
				<? } ?>
			</tr>
		<? } ?>
	</tbody>
</table>
