<?
/**
 * @var int $index
 * @var bool $isRequired
 * @var PollItem $pollItem
 */
?>
<fieldset class="closed-group">
	<input type="hidden" name="poll-item-id-<?= $index ?>" value="<? if(isset($pollItem->PollItemId)){ ?><?= $pollItem->PollItemId ?><? } ?>" />
	<label>
		<span>Sort order</span>
		<input type="text" inputmode="numeric" pattern="^[0-9]{1,4}$" name="poll-item-sort-order-<?= $index ?>" min="1" max="255" required="required" value="<?= $pollItem->SortOrder ?? $index ?>" />
	</label>
	<label>
		<span>Name</span>
		<span>Markdown accepted.</span>
		<input type="text" name="poll-item-name-<?= $index ?>" maxlength="255"<? if($isRequired){ ?> required="required"<? } ?> value="<?= Formatter::EscapeHtml($pollItem->Name ?? '') ?>" />
	</label>
	<label>
		<span>Description</span>
		<span>Markdown accepted.</span>
		<input type="text" name="poll-item-description-<?= $index ?>" value="<?= Formatter::EscapeHtml($pollItem->Description ?? '') ?>" />
	</label>
</fieldset>
