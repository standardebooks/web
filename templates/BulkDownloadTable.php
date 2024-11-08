<?
/**
 * @var string $label
 * @var array<stdClass> $collections
 */
?>
<table class="download-list">
	<caption aria-hidden="hidden">Scroll right →</caption>
	<thead>
		<tr class="mid-header">
			<th scope="col"><?= Formatter::EscapeHtml($label) ?></th>
			<th scope="col">Ebooks</th>
			<th scope="col">Updated</th>
			<th scope="col" colspan="10">Ebook format</th>
		</tr>
	</thead>
	<tbody>
		<? foreach($collections as $collection){ ?>
			<tr>
				<td class="row-header"><a href="<?= $collection->Url ?>"><?= Formatter::EscapeHtml($collection->Label) ?></a></td>
				<td class="number"><?= Formatter::EscapeHtml(number_format($collection->EbookCount)) ?></td>
				<td class="number"><?= Formatter::EscapeHtml($collection->UpdatedString) ?></td>
				<? foreach($collection->ZipFiles as $item){ ?>
					<td class="download"><a href="<?= $item->Url ?>"><?= $item->Type ?></a></td>
					<td>(<?= Formatter::EscapeHtml($item->Size) ?>)</td>
				<? } ?>
			</tr>
		<? } ?>
	</tbody>
</table>
