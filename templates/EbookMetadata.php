<?
/**
 * @var Ebook $ebook
 */
?>
<h2>Metadata</h2>
<table class="admin-table">
	<tbody>
		<tr>
			<td>Ebook ID:</td>
			<td><?= $ebook->EbookId ?></td>
		</tr>
		<? if($ebook->IsPlaceholder() && $ebook->EbookPlaceholder !== null){ ?>
			<tr>
				<td>Is wanted:</td>
				<td><? if($ebook->EbookPlaceholder->IsWanted){ ?>☑<? }else{ ?>☐<? } ?></td>
			</tr>
			<? if($ebook->EbookPlaceholder->IsWanted){ ?>
				<tr>
					<td>Is Patron selection:</td>
					<td><? if($ebook->EbookPlaceholder->IsPatron){ ?>☑<? }else{ ?>☐<? } ?></td>
				</tr>
			<? } ?>
			<tr>
				<td>Difficulty:</td>
				<td><?= ucfirst($ebook->EbookPlaceholder->Difficulty->value ?? '') ?></td>
			</tr>
		<? } ?>
	</tbody>
</table>

<? if(sizeof($ebook->Projects) > 0){ ?>
	<h2>Projects</h2>
	<?= Template::ProjectsTable(['projects' => $ebook->Projects, 'includeTitle' => false]) ?>
<? } ?>
