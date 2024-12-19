<?
/**
 * @var Ebook $ebook
 */

$showPlaceholderMetadata = $showPlaceholderMetadata ?? false;
?>
<section id="metadata">
	<h2>Metadata</h2>
	<table class="admin-table">
		<tbody>
			<tr>
				<td>Ebook ID:</td>
				<td><?= $ebook->EbookId ?></td>
			</tr>
		</tbody>
	</table>
</section>

<? if($showPlaceholderMetadata && $ebook->IsPlaceholder() && $ebook->EbookPlaceholder !== null){ ?>
	<section id="placeholder-metadata">
		<h2>Placeholder metadata</h2>
		<p><a href="<?= $ebook->EditUrl ?>">Edit placeholder</a></p>
		<details>
			<summary>Delete placeholder</summary>
			<form method="<?= Enums\HttpMethod::Post->value ?>" action="<?= $ebook->Url ?>">
				<input type="hidden" name="_method" value="<?= Enums\HttpMethod::Delete->value ?>" />
				<p>Delete the <code>Ebook</code>, <code>EbookPlaceholder</code>, <code>Project</code>, and other related objects.</p>
				<div class="footer">
					<button class="delete">Delete</button>
				</div>
			</form>
		</details>
		<table class="admin-table">
			<tbody>
				<tr>
					<td>Is wanted:</td>
					<td><? if($ebook->EbookPlaceholder->IsWanted){ ?>☑<? }else{ ?>☐<? } ?></td>
				</tr>
				<? if($ebook->EbookPlaceholder->IsWanted){ ?>
					<tr>
						<td>Is Patron selection:</td>
						<td><? if($ebook->EbookPlaceholder->IsPatron){ ?>☑<? }else{ ?>☐<? } ?></td>
					</tr>
					<? if($ebook->EbookPlaceholder->Difficulty !== null){ ?>
						<tr>
							<td>Difficulty:</td>
							<td><?= ucfirst($ebook->EbookPlaceholder->Difficulty->value) ?></td>
						</tr>
					<? } ?>
				<? } ?>
			</tbody>
		</table>
	</section>
<? } ?>
