<?
/**
 * @var int $index
 * @var ?CollectionMembership $collectionMembership
 */
?>
<fieldset class="closed-group">
	<label class="icon collection">
		<span>Collection <?= $index ?></span>
		<input
			type="text"
			name="collection-name-<?= $index ?>"
			list="collection-names"
			value="<?= Formatter::EscapeHtml($collectionMembership?->Collection->Name) ?>"
		/>
	</label>
	<label class="icon indent">
		<span>Type</span>
		<select name="collection-type-<?= $index ?>">
			<option value="">&#160;</option>
			<option value="<?= Enums\CollectionType::Series->value ?>"<? if($collectionMembership?->Collection->Type == Enums\CollectionType::Series){ ?> selected="selected"<? } ?>>Series</option>
			<option value="<?= Enums\CollectionType::Set->value ?>"<? if($collectionMembership?->Collection->Type == Enums\CollectionType::Set){ ?> selected="selected"<? } ?>>Set</option>
		</select>
	</label>
	<label class="icon ordered-list">
		<span>Number in collection</span>
		<input
			type="text"
			name="collection-membership-sequence-number-<?= $index ?>"
			inputmode="numeric"
			pattern="^[0-9]{1,3}$"
			autocomplete="off"
			value="<?= Formatter::EscapeHtml((string)$collectionMembership?->SequenceNumber) ?>"
		/>
	</label>
	<label class="icon book">
		<span>Title in collection</span>
		<span>E.g. this ebook is an omnibus but the collection refers to a specific item.</span>
		<input
			type="text"
			name="collection-membership-title-in-collection-<?= $index ?>"
			autocomplete="off"
			value="<?= Formatter::EscapeHtml($collectionMembership?->TitleInCollection) ?>"
		/>
	</label>
</fieldset>
