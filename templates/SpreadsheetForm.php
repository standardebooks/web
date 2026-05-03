<?
/**
 * @var Spreadsheet $spreadsheet
 */

$isEditForm ??= false;
?>
<label>
	<span>Title</span>
	<input
		type="text"
		name="spreadsheet-title"
		maxlength="255"
		required="required"
		value="<?= Formatter::EscapeHtml($spreadsheet->Title ?? '') ?>"
	/>
</label>
<label>
	<span>URL</span>
	<input
		type="url"
		name="spreadsheet-external-url"
		maxlength="255"
		required="required"
		value="<?= Formatter::EscapeHtml($spreadsheet->ExternalUrl ?? '') ?>"
	/>
</label>
<label>
	<span>Category</span>
	<select name="spreadsheet-category" required="required">
		<option value="<?= Enums\SpreadsheetCategory::Available->value ?>"<? if(isset($spreadsheet->Category) && $spreadsheet->Category == Enums\SpreadsheetCategory::Available){ ?> selected="selected"<? } ?>>Available</option>
		<option value="<?= Enums\SpreadsheetCategory::HelpWanted->value ?>"<? if(isset($spreadsheet->Category) && $spreadsheet->Category == Enums\SpreadsheetCategory::HelpWanted){ ?> selected="selected"<? } ?>>Incomplete research</option>
		<option value="<?= Enums\SpreadsheetCategory::Incomplete->value ?>"<? if(isset($spreadsheet->Category) && $spreadsheet->Category == Enums\SpreadsheetCategory::Incomplete){ ?> selected="selected"<? } ?>>Incomplete because not fully P.D. yet</option>
		<option value="<?= Enums\SpreadsheetCategory::Complete->value ?>"<? if(isset($spreadsheet->Category) && $spreadsheet->Category == Enums\SpreadsheetCategory::Complete){ ?> selected="selected"<? } ?>>Complete</option>
		<option value="<?= Enums\SpreadsheetCategory::Legacy->value ?>"<? if(isset($spreadsheet->Category) && $spreadsheet->Category == Enums\SpreadsheetCategory::Legacy){ ?> selected="selected"<? } ?>>Legacy</option>
	</select>
</label>
<label>
	<span>Sort order</span>
	<? /* Not using `<input type="number">` for now, see <https://technology.blog.gov.uk/2020/02/24/why-the-gov-uk-design-system-team-changed-the-input-type-for-numbers/>. */ ?>
	<input
		type="text"
		name="spreadsheet-sort-order"
		inputmode="numeric"
		pattern="^[0-9]{1,4}$"
		value="<?= $spreadsheet->SortOrder ?? '' ?>"
	/>
</label>
<label>
	<span>Notes</span>
	<span>Markdown accepted.</span>
	<textarea name="spreadsheet-notes"><?= Formatter::EscapeHtml($spreadsheet->Notes ?? '') ?></textarea>
</label>
<div class="footer">
	<button>
		<? if($isEditForm){ ?>
			Save changes
		<? }else{ ?>
			Submit
		<? } ?>
	</button>
</div>
