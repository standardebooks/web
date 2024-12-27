<?

$ebooks = [];
if(isset($difficulty)){
	$ebooks = Ebook::GetWantedByDifficulty($difficulty);
}
$showPlaceholderMetadata = $showPlaceholderMetadata ?? false;

?>
<ul>
	<? foreach($ebooks as $ebook){ ?>
		<li>
			<? if(isset($ebook->EbookPlaceholder->TranscriptionUrl)){ ?><a href="<?= $ebook->EbookPlaceholder->TranscriptionUrl ?>"><? } ?>
			<?= Formatter::EscapeHtml($ebook->Title) ?><? if(isset($ebook->EbookPlaceholder->TranscriptionUrl)){ ?></a><? } ?>
			<? if(sizeof($ebook->CollectionMemberships) > 0){ ?>
				(<? foreach($ebook->CollectionMemberships as $index => $collectionMembership){ ?><?= Template::CollectionFormatted(['collectionMembership' => $collectionMembership]) ?><? if($index < sizeof($ebook->CollectionMemberships) - 1){ ?>, <? } ?><? } ?>)
			<? } ?>
			by <?= Formatter::EscapeHtml($ebook->AuthorsString) ?><? if($ebook->ContributorsHtml != ''){ ?>. <? } ?>
			<?= $ebook->ContributorsHtml ?>
			<? if(isset($ebook->EbookPlaceholder->Notes)){ ?>(<?= Formatter::MarkdownToInlineHtml($ebook->EbookPlaceholder->Notes) ?>)<? } ?>
			<? if($showPlaceholderMetadata){ ?>
				<p>Ebook ID: <?= $ebook->EbookId ?>, <a href="<?= $ebook->Url ?>">View placeholder</a></p>
			<? } ?>
		</li>
	<? } ?>
</ul>
