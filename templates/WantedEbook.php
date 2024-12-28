<?
/**
 * @var Ebook $ebook
 */

if(!isset($ebook->EbookPlaceholder)){
	return;
}

$showPlaceholderMetadata = $showPlaceholderMetadata ?? false;
?>
<li>
	<p>
		<? if(isset($ebook->EbookPlaceholder->TranscriptionUrl)){ ?><a href="<?= $ebook->EbookPlaceholder->TranscriptionUrl ?>"><? } ?><i><?= Formatter::EscapeHtml($ebook->Title) ?></i><? if(isset($ebook->EbookPlaceholder->TranscriptionUrl)){ ?></a><? } ?>


		by <?= Formatter::EscapeHtml($ebook->AuthorsString) ?>. <?= $ebook->ContributorsHtml ?>

		<? foreach($ebook->CollectionMemberships as $index => $collectionMembership){ ?>
			<? if($index == 0){ ?><?= Template::CollectionDescriptor(['collectionMembership' => $collectionMembership]) ?><? }else{ ?><?= lcfirst(Template::CollectionDescriptor(['collectionMembership' => $collectionMembership])) ?><? } ?><? if($index < sizeof($ebook->CollectionMemberships) - 1){ ?>, <? } ?><? if($index == sizeof($ebook->CollectionMemberships) - 1){ ?>.<? } ?>
		<? } ?>

		<? if(isset($ebook->EbookPlaceholder->Notes)){ ?>
			<?= Formatter::MarkdownToHtml($ebook->EbookPlaceholder->Notes, true) ?>
		<? } ?>
		<? if($showPlaceholderMetadata){ ?>
			— <a href="<?= $ebook->Url ?>">View placeholder.</a>
		<? } ?>

		<? if($ebook->EbookPlaceholder->IsPatron){ ?>
			<a class="patron-selection" href="/donate#patrons-circle">Patron selection!</a>
		<? } ?>
	</p>
</li>
