<?
/**
 * @var array<Ebook> $ebooks
 * @var bool $showPlaceholderMetadata
 */
?>
<ul class="wanted-list">
	<? foreach($ebooks as $ebook){ ?>
		<li>
			<p>
				<? if(isset($ebook->EbookPlaceholder->TranscriptionUrl)){ ?><a href="<?= Formatter::EscapeHtml($ebook->EbookPlaceholder->TranscriptionUrl) ?>"><? } ?><i><?= Formatter::EscapeHtml($ebook->Title) ?></i><? if(isset($ebook->EbookPlaceholder->TranscriptionUrl)){ ?></a><? } ?>


				by <?= Formatter::EscapeHtml($ebook->AuthorsString) ?>. <?= $ebook->ContributorsHtml ?>

				<? foreach($ebook->GetCollectionsHtml(false, false) as $index => $line){ ?>
					<? if($index == 0){ ?><?= $line ?><? }else{ ?><?= lcfirst($line) ?><? } ?><? if($index < sizeof($ebook->CollectionMemberships) - 1){ ?>, <? } ?><? if($index == sizeof($ebook->CollectionMemberships) - 1){ ?>.<? } ?>
				<? } ?>

				<? if(isset($ebook->EbookPlaceholder->Notes)){ ?>
					<?= Formatter::MarkdownToHtml($ebook->EbookPlaceholder->Notes, true) ?>
				<? } ?>
				<? if($showPlaceholderMetadata){ ?>
					— <a href="<?= $ebook->Url ?>">View placeholder.</a>
				<? } ?>

				<? if($ebook->IsPatronSelection){ ?>
					<a class="patron-selection" href="/donate#patrons-circle">Patron selection!</a>
				<? } ?>
			</p>
		</li>
	<? } ?>
</ul>
