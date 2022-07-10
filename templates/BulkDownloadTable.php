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
		<? foreach($collections as $collection => $items){ ?>
		<tr>
			<td class="row-header"><a href="/collections/<?= Formatter::MakeUrlSafe($items[0]->Label) ?>"><?= Formatter::ToPlainText($items[0]->Label) ?></a></td>
			<td class="number"><?= Formatter::ToPlainText(number_format($items[0]->Count)) ?></td>
			<td class="number"><?= Formatter::ToPlainText($items[0]->UpdatedString) ?></td>

			<? foreach($items as $item){ ?>
				<td class="download"><a href="<?= $item->Url ?>" download=""><?= $item->Type ?></a></td>
				<td>(<?= Formatter::ToPlainText($item->Size) ?>)</td>
			<? } ?>
		</tr>
		<? } ?>
	</tbody>
</table>
