<?
/**
 * @var NewsletterMailing $newsletterMailing
 */

$newsletters = Newsletter::GetAll();
$isEditForm ??= false;
$addFooter ??= true;
?>
<label class="select icon emails">
	<span>Newsletter</span>
	<select name="newsletter-mailing-newsletter-id">
		<? foreach($newsletters as $newsletter){ ?>
			<option value="<?= $newsletter->NewsletterId ?>"<? if(isset($newsletterMailing->NewsletterId) && $newsletterMailing->NewsletterId == $newsletter->NewsletterId){ ?> selected="selected"<? } ?>><?= Formatter::EscapeHtml($newsletter->Name) ?></option>
		<? } ?>
	</select>
</label>
<? if($isEditForm){ ?>
	<label class="select icon calendar-check">
		<span>Status</span>
		<select name="newsletter-mailing-status">
			<option value="<?= Enums\QueueStatus::Queued->value ?>"<? if($newsletterMailing->Status == Enums\QueueStatus::Queued){ ?> selected="selected"<? } ?>>Queued</option>
			<option value="<?= Enums\QueueStatus::Completed->value ?>"<? if($newsletterMailing->Status == Enums\QueueStatus::Completed){ ?> selected="selected"<? } ?>>Completed</option>
			<option value="<?= Enums\QueueStatus::Canceled->value ?>"<? if($newsletterMailing->Status == Enums\QueueStatus::Canceled){ ?> selected="selected"<? } ?>>Canceled</option>
			<option value="<?= Enums\QueueStatus::Failed->value ?>"<? if($newsletterMailing->Status == Enums\QueueStatus::Failed){ ?> selected="selected"<? } ?>>Failed</option>
		</select>
	</label>
<? } ?>
<label class="icon year">
	<span>Send on</span>
	<span>Time zone is <?= SITE_TZ->getName() ?>.</span>
	<? /* `SendOnTimestamp` is stored as UTC in the object, but must be in the `SITE_TZ` time zone for this element. */ ?>
	<input type="datetime-local" name="newsletter-mailing-send-on" required="required" value="<? if(isset($newsletterMailing->SendOn)){ ?><?= $newsletterMailing->SendOn->setTimezone(SITE_TZ)->format(Enums\DateTimeFormat::Html->value) ?><? } ?>" />
</label>
<label class="icon user">
	<span>From name</span>
	<input type="text" name="newsletter-mailing-from-name" value="<?= Formatter::EscapeHtml($newsletterMailing->FromName) ?>" maxlength="255" required="required" />
</label>
<label class="icon email">
	<span>From email</span>
	<input type="email" name="newsletter-mailing-from-email" required="required" value="<?= Formatter::EscapeHtml($newsletterMailing->FromEmail ?? '') ?>" maxlength="255" />
</label>
<label class="icon pencil-square">
	<span>Subject</span>
	<span>Optimal length is less than 45 characters, or 7 words</span>
	<input type="text" name="newsletter-mailing-subject" required="required" value="<?= Formatter::EscapeHtml($newsletterMailing->Subject ?? '') ?>" maxlength="255" />
</label>
<? if(!$isEditForm){ ?>
	<label class="checkbox">
		<input type="hidden" name="add-footer" value="false" />
		<input type="checkbox" name="add-footer" value="true"<? if($addFooter){ ?> checked="checked"<? } ?>/>
		<span>Auto-include footer</span>
	</label>
<? } ?>
<label>
	<span>Body HTML</span>
	<? if(!$isEditForm){ ?>
		<span>If no <code>&lt;body&gt;</code>, HTML will be auto-wrapped with a complete HTML document.</span>
	<? } ?>
	<textarea name="newsletter-mailing-body-html"><?= Formatter::EscapeHtml($newsletterMailing->BodyHtml ?? '') ?></textarea>
</label>
<label>
	<span>Body Text</span>
	<textarea name="newsletter-mailing-body-text"><?= Formatter::EscapeHtml($newsletterMailing->BodyText ?? '') ?></textarea>
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
