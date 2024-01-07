<?
$artwork = $artwork ?? null;
?>
<? if($artwork !== null){ ?>
<? if($artwork->Status === COVER_ARTWORK_STATUS_APPROVED){ ?>Approved<? } ?>
<? if($artwork->Status === COVER_ARTWORK_STATUS_DECLINED){ ?>Declined<? } ?>
<? if($artwork->Status === COVER_ARTWORK_STATUS_UNVERIFIED){ ?>Unverified<? } ?>
<? if($artwork->Status === COVER_ARTWORK_STATUS_IN_USE){ ?>In use<? if($artwork->Ebook !== null && $artwork->Ebook->Url !== null){ ?> for <i><a href="<?= $artwork->Ebook->Url ?>"><?= Formatter::ToPlainText($artwork->Ebook->Title) ?></a></i><? } ?><? } ?>
<? } ?>
