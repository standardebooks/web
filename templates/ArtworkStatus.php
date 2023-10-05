<?

$artwork = $artwork ?? null;
?>
<? if($artwork !== null){ ?>
<? if($artwork->Status === 'approved'){ ?>Approved<? } ?>
<? if($artwork->Status === 'declined'){ ?>Declined<? } ?>
<? if($artwork->Status === 'unverified'){ ?>Unverified<? } ?>
<? if($artwork->Status === 'in_use'){ ?>In use<? if($artwork->Ebook !== null && $artwork->Ebook->Url !== null){ ?> by <a href="<?= $artwork->Ebook->Url ?>" property="schema:url"><span property="schema:name"><?= Formatter::ToPlainText($artwork->Ebook->Title) ?></span></a><? } ?><? } ?>
<? } ?>
