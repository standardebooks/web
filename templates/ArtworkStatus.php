<?
/**
 * @var Artwork $artwork
 */
?>
<? if($artwork !== null){ ?>
	<? if($artwork->Status !== null){ ?>
		<?= ucfirst($artwork->Status->value) ?>
	<? } ?>
	<? if($artwork->EbookUrl !== null){ ?>
		— in use
		<? if($artwork->EbookUrl !== null){ ?>
			by
			<? if($artwork->Ebook !== null && $artwork->Ebook->Url !== null){ ?>
				<i>
					<a href="<?= $artwork->Ebook->Url ?>"><?= Formatter::EscapeHtml($artwork->Ebook->Title) ?></a>
				</i><? if($artwork->Ebook->IsPlaceholder()){ ?>(unreleased)<? } ?>
			<? }else{ ?>
				<code><?= Formatter::EscapeHtml($artwork->EbookUrl) ?></code> (unreleased)
			<? } ?>
		<? } ?>
	<? } ?>
<? } ?>
