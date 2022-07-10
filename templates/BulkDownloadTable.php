<table class="download-list">
	<thead>
		<tr class="mid-header">
			<th scope="col"><?= $label ?></th>
			<th scope="col">Ebooks</th>
			<th scope="col">Updated</th>
			<th scope="col" colspan="10">Ebook format</th>
		</tr>
	</thead>
	<tbody>
		<? foreach($collections as $collection){ ?>
		<tr>
			<td class="row-header"><a href="<?= $collection->Url ?>"><?= Formatter::ToPlainText($collection->Label) ?></a></td>
			<td class="number"><?= Formatter::ToPlainText(number_format($collection->EbookCount)) ?></td>
			<td class="number"><?= Formatter::ToPlainText($collection->UpdatedString) ?></td>

			<? foreach($collection->ZipFiles as $item){ ?>
				<td class="download"><a href="<?= $item->Url ?>"><?= $item->Type ?></a></td>
				<td>(<?= Formatter::ToPlainText($item->Size) ?>)</td>
			<? } ?>
		</tr>
		<? } ?>
	</tbody>
</table>
