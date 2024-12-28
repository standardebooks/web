<?
use function Safe\preg_replace;

$collectionMembership = $collectionMembership ?? null;
$collection = $collectionMembership?->Collection;
$sequenceNumber = $collectionMembership?->SequenceNumber;
?>
<? if($sequenceNumber !== null){ ?>№ <?= number_format($sequenceNumber) ?> in the<? }else{ ?>Part of the<? } ?> <a href="<?= $collection->Url ?>" property="schema:isPartOf"><?= Formatter::EscapeHtml(preg_replace('/^The /ius', '', (string)$collection->Name)) ?></a>
<? if($collection->Type !== null){ ?>
	<? if(substr_compare(mb_strtolower($collection->Name), mb_strtolower($collection->Type->value), -strlen(mb_strtolower($collection->Type->value))) !== 0){ ?><?= $collection->Type->value ?><? } ?>
<? }else{ ?>
	collection
<? } ?>
