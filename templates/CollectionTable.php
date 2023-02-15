<table class="download-list">
	<thead>
		<tr class="mid-header">
			<th scope="col">Collection</th>
		</tr>
	</thead>
	<tbody>
		<? foreach($collections as $collection){ ?>
		<tr>
			<td class="row-header"><a href="<?= $collection->Url ?>"><?= Formatter::ToPlainText($collection->Name) ?></a></td>
		</tr>
		<? } ?>
	</tbody>
</table>
