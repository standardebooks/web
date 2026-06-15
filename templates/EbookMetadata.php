<?
/**
 * @var Ebook $ebook
 */

$showPlaceholderMetadata ??= false;
?>
<section id="metadata" class="admin">
	<h2>Metadata</h2>
	<dl>
		<dt>Ebook ID:</dt>
		<dd class="id"><?= $ebook->EbookId ?></dd>
		<dt>Is Patron selection:</dt>
		<dd><? if($ebook->IsPatronSelection){ ?>☑<? }else{ ?>☐<? } ?></dd>
	</dl>
</section>

<? if($showPlaceholderMetadata && $ebook->IsPlaceholder() && $ebook->EbookPlaceholder !== null){ ?>
	<section id="placeholder-metadata" class="admin">
		<h2>Placeholder metadata</h2>
		<ul role="menu">
			<li><a href="<?= $ebook->EditUrl ?>">Edit placeholder</a></li>
			<li><a href="<?= $ebook->DeleteUrl ?>">Delete placeholder</a></li>
		</ul>
		<dl>
			<dt>Is wanted:</dt>
			<dd><? if($ebook->EbookPlaceholder->IsWanted){ ?>☑<? }else{ ?>☐<? } ?></dd>
			<? if($ebook->EbookPlaceholder->IsWanted && $ebook->EbookPlaceholder->Difficulty !== null){ ?>
				<dt>Difficulty:</dt>
				<dd><?= ucfirst($ebook->EbookPlaceholder->Difficulty->value) ?></dd>
			<? } ?>
		</dl>
	</section>
<? } ?>
