<?
$artwork = $artwork ?? null;
?>
<? if($artwork !== null){ ?>
<? if($artwork->Status === COVER_ARTWORK_STATUS_APPROVED){ ?>Approved<? } ?>
<? if($artwork->Status === COVER_ARTWORK_STATUS_DECLINED){ ?>Declined<? } ?>
<? if($artwork->Status === COVER_ARTWORK_STATUS_UNVERIFIED){ ?>Unverified<? } ?>
<? if($artwork->Status === COVER_ARTWORK_STATUS_IN_USE){ ?>In use<? if($artwork->EbookWwwFilesystemPath !== null){ ?> for <? if($artwork->Ebook !== null && $artwork->Ebook->Url !== null){ ?><i><a href="<?= $artwork->Ebook->Url ?>"><?= Formatter::ToPlainText($artwork->Ebook->Title) ?></a></i><? }else{ ?><code><?= Formatter::ToPlainText($artwork->EbookWwwFilesystemPath) ?></code> (Unreleased)<? } ?><? } ?><? } ?>
<? } ?>
