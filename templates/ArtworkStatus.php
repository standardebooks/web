<?

$artwork = $artwork ?? null;
?>
<? if($artwork !== null){ ?>
	<? if($artwork->Status === "approved"){ ?>
		Approved
	<? }else if($artwork->Status === "in_use"){ ?>
		In use
		<? if($artwork->Ebook !== null && $artwork->Ebook->Url !== null){ ?>
			 by <a href="<?= $artwork->Ebook->Url ?>" property="schema:url"><span property="schema:name"><?= Formatter::ToPlainText($artwork->Ebook->Title) ?></span></a>
		<? } ?>
	<? }else{ ?>
		<?= Formatter::ToPlainText($artwork->Status) ?>
	<? } ?>
<? } ?>
