<?
use function Safe\preg_replace;

/**
 * @var ?CollectionMembership $collectionMembership
 */

$collection = $collectionMembership?->Collection;
$sequenceNumber = $collectionMembership?->SequenceNumber;
$titleInCollection = $collectionMembership?->TitleInCollection;
?>
<? if($collection !== null){ ?>
	<? if($sequenceNumber !== null){ ?>№ <?= number_format($sequenceNumber) ?> in the<? }else{ ?>Part of the<? } ?> <a href="<?= $collection->Url ?>" property="schema:isPartOf"><?= Formatter::EscapeHtml(preg_replace('/^The /ius', '', $collection->Name)) ?></a>
<? } ?>
<? if($collection?->Type !== null){ ?>
	<? if(substr_compare(mb_strtolower($collection->Name), mb_strtolower($collection->Type->value), -strlen(mb_strtolower($collection->Type->value))) !== 0){ ?><?= $collection->Type->value ?><? } ?>
<? }else{ ?>
	collection
<? } ?>
<? if($titleInCollection !== null){ ?>
	as <?= Formatter::EscapeHtml($titleInCollection) ?>
<? } ?>
