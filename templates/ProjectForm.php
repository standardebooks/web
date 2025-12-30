<?
/**
 * @var Project $project
 */

$areFieldsRequired ??= true;
$isEditForm ??= false;

$managers = User::GetAllByCanManageProjects();
$reviewers = User::GetAllByCanReviewProjects();
$pastProducers = User::GetNamesByHasProducedProject();
?>
<? if(!$isEditForm){ ?>
	<input type="hidden" name="project-ebook-id" value="<?= $project->EbookId ?? '' ?>" />
<? } ?>

<datalist id="editors">
	<? foreach($pastProducers as $row){ ?>
		<option value="<?= Formatter::EscapeHtml($row->ProducerName) ?>"><?= Formatter::EscapeHtml($row->ProducerName) ?></option>
	<? } ?>
</datalist>

<label class="icon user">
	<span>Producer name</span>
	<input
		type="text"
		name="project-producer-name"
		list="editors"
		<? if($areFieldsRequired){ ?>
			required="required"
		<? } ?>
		value="<?= Formatter::EscapeHtml($project->ProducerName ?? '') ?>"
	/>
</label>

<label>
	<span>Producer Email</span>
	<input
		type="email"
		name="project-producer-email"
		value="<?= Formatter::EscapeHtml($project->ProducerEmail) ?>"
	/>
</label>

<label class="icon user">
	<span>Manager</span>
	<? if(!$isEditForm){ ?>
		<span>Leave blank to auto-assign.</span>
	<? } ?>
	<select name="project-manager-user-id">
		<? if(!$isEditForm){ ?>
			<option value="">&#160;</option>
		<? } ?>
		<? foreach($managers as $manager){ ?>
			<option value="<?= $manager->UserId ?>"<? if(isset($project->ManagerUserId) && $project->ManagerUserId == $manager->UserId){ ?> selected="selected"<? } ?>><?= Formatter::EscapeHtml($manager->Name) ?></option>
		<? } ?>
	</select>
</label>

<label class="icon user">
	<span>Reviewer</span>
	<? if(!$isEditForm){ ?>
		<span>Leave blank to auto-assign.</span>
	<? } ?>
	<select name="project-reviewer-user-id">
		<? if(!$isEditForm){ ?>
			<option value="">&#160;</option>
		<? } ?>
		<? foreach($reviewers as $reviewer){ ?>
			<option value="<?= $reviewer->UserId ?>"<? if(isset($project->ReviewerUserId) && $project->ReviewerUserId == $reviewer->UserId){ ?> selected="selected"<? } ?>><?= Formatter::EscapeHtml($reviewer->Name) ?></option>
		<? } ?>
	</select>
</label>

<label class="icon meter">
	<span>Status</span>
	<select name="project-status">
		<option value="<?= Enums\ProjectStatusType::InProgress->value ?>"<? if($project->Status == Enums\ProjectStatusType::InProgress){?> selected="selected"<? } ?>>In progress</option>
		<option value="<?= Enums\ProjectStatusType::AwaitingReview->value ?>"<? if($project->Status == Enums\ProjectStatusType::AwaitingReview){?> selected="selected"<? } ?>>Awaiting review</option>
		<option value="<?= Enums\ProjectStatusType::Reviewed->value ?>"<? if($project->Status == Enums\ProjectStatusType::Reviewed){?> selected="selected"<? } ?>>Reviewed</option>
		<option value="<?= Enums\ProjectStatusType::Stalled->value ?>"<? if($project->Status == Enums\ProjectStatusType::Stalled){?> selected="selected"<? } ?>>Stalled</option>
		<option value="<?= Enums\ProjectStatusType::Completed->value ?>"<? if($project->Status == Enums\ProjectStatusType::Completed){?> selected="selected"<? } ?>>Completed</option>
		<option value="<?= Enums\ProjectStatusType::Abandoned->value ?>"<? if($project->Status == Enums\ProjectStatusType::Abandoned){?> selected="selected"<? } ?>>Abandoned</option>
	</select>
</label>

<label>
	<span>Automatically update status</span>
	<input type="hidden" name="project-is-status-automatically-updated" value="false" />
	<input
		type="checkbox"
		name="project-is-status-automatically-updated"
		<? if($project->IsStatusAutomaticallyUpdated){ ?>checked="checked"<? } ?>
	/>
</label>

<label>
	<span>VCS URL</span>
	<input
		type="url"
		name="project-vcs-url"
		autocomplete="off"
		value="<?= Formatter::EscapeHtml($project->VcsUrl ?? '') ?>"
	/>
</label>

<label>
	<span>Discussion URL</span>
	<input
		type="url"
		name="project-discussion-url"
		autocomplete="off"
		value="<?= Formatter::EscapeHtml($project->DiscussionUrl) ?>"
	/>
</label>
