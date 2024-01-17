<?
$artwork = $artwork ?? null;
?>
<? if($artwork !== null){ ?>
<? if($artwork->Status == ArtworkStatus::Approved){ ?>Approved<? } ?>
<? if($artwork->Status == ArtworkStatus::Declined){ ?>Declined<? } ?>
<? if($artwork->Status == ArtworkStatus::Unverified){ ?>Unverified<? } ?>
<? if($artwork->EbookWwwFilesystemPath !== null){ ?> — in use<? if($artwork->EbookWwwFilesystemPath !== null){ ?> by <? if($artwork->Ebook !== null && $artwork->Ebook->Url !== null){ ?><i><a href="<?= $artwork->Ebook->Url ?>"><?= Formatter::ToPlainText($artwork->Ebook->Title) ?></a></i><? }else{ ?><code><?= Formatter::ToPlainText($artwork->EbookWwwFilesystemPath) ?></code> (unreleased)<? } ?><? } ?><? } ?>
<? } ?>
