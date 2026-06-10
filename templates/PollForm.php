<?
/**
 * @var Poll $poll
 */

$isEditForm ??= false;
$pollItems = $poll->PollItems ?? [];
$pollItemCount = max(sizeof($pollItems) + 1, 4);
?>
<fieldset>
	<label>
		<span>Name</span>
		<input type="text" name="poll-name" maxlength="255" required="required" value="<?= Formatter::EscapeHtml($poll->Name ?? '') ?>" />
	</label>
	<label>
		<span>Description</span>
		<span>Markdown accepted.</span>
		<textarea name="poll-description"><?= Formatter::EscapeHtml($poll->Description ?? '') ?></textarea>
	</label>
	<label class="icon year">
		<span>Start</span>
		<span>Time zone is <?= Formatter::EscapeHtml(SITE_TZ->getName()) ?>.</span>
		<input type="datetime-local" name="poll-start" value="<? if(isset($poll->Start)){ ?><?= $poll->Start->setTimezone(SITE_TZ)->format(Enums\DateTimeFormat::Html->value) ?><? } ?>" />
	</label>
	<label class="icon year">
		<span>End</span>
		<span>Time zone is <?= Formatter::EscapeHtml(SITE_TZ->getName()) ?>.</span>
		<input type="datetime-local" name="poll-end" value="<? if(isset($poll->End)){ ?><?= $poll->End->setTimezone(SITE_TZ)->format(Enums\DateTimeFormat::Html->value) ?><? } ?>" />
	</label>
</fieldset>
<fieldset class="poll-items">
	<legend>Poll options</legend>
	<? for($i = 0; $i < $pollItemCount; $i++){ ?>
		<?
			$pollItem = $pollItems[$i] ?? new PollItem();
			$pollItemIndex = $i + 1;
		?>
		<?= Template::PollItemFieldset(index: $pollItemIndex, isRequired: !$isEditForm && $i < 2, pollItem: $pollItem) ?>
	<? } ?>
</fieldset>
<div class="footer">
	<button>
		<? if($isEditForm){ ?>
			Save changes
		<? }else{ ?>
			Submit
		<? } ?>
	</button>
</div>
