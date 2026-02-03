<?
use function Safe\preg_match;
use function Safe\preg_replace;

/**
 * @var ?CollectionMembership $collectionMembership
 */

$collection = $collectionMembership?->Collection;
$sequenceNumber = $collectionMembership?->SequenceNumber;
$titleInCollection = $collectionMembership?->TitleInCollection;
$includeEndingPeriod ??= true;
?>
<? if($collection !== null){ ?>
	<? if($sequenceNumber !== null){ ?>№ <?= number_format($sequenceNumber) ?> in the<? }else{ ?>Part of the<? } ?> <a href="<?= $collection->Url ?>" property="schema:isPartOf"><?= Formatter::EscapeHtml(preg_replace('/^The /ius', '', $collection->Name)) ?></a><? if($includeEndingPeriod && $titleInCollection === null && $collection->Type === null){ ?>.<? } ?>
<? } ?>
<? if($collection?->Type !== null){ ?>
	<? if(substr_compare(mb_strtolower($collection->Name), mb_strtolower($collection->Type->value), -strlen(mb_strtolower($collection->Type->value))) !== 0){ ?><?= $collection->Type->value ?><? } ?><? if($includeEndingPeriod && $titleInCollection === null){ ?>.<? } ?>
<? }else{ ?>
	collection<? if($includeEndingPeriod && $titleInCollection === null){ ?>.<? } ?>
<? } ?>
<? if($titleInCollection !== null){ ?>
	as “<?= Formatter::EscapeHtml($titleInCollection) ?><? if($includeEndingPeriod && preg_match('/\p{L}$/u', $titleInCollection)){ ?>.<? } ?>”
<? } ?>
