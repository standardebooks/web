<?
$project = $project ?? new Project();
$managers = User::GetAllByCanManageProjects();
$reviewers = User::GetAllByCanReviewProjects();
?>
<label class="icon user">
	<span>Producer name</span>
	<input
		type="text"
		name="project-producer-name"
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
	<span>
		<select name="project-manager-user-id">
			<? foreach($managers as $manager){ ?>
				<option value="<?= $manager->UserId ?>"<? if(isset($project->ManagerUserId) && $project->ManagerUserId == $manager->UserId){ ?> selected="selected"<? } ?>><?= Formatter::EscapeHtml($manager->Name) ?></option>
			<? } ?>
		</select>
	</span>
</label>

<label class="icon user">
	<span>Reviewer</span>
	<span>
		<select name="project-reviewer-user-id">
			<? foreach($reviewers as $reviewer){ ?>
				<option value="<?= $reviewer->UserId ?>"<? if(isset($project->ReviewerUserId) && $project->ReviewerUserId == $reviewer->UserId){ ?> selected="selected"<? } ?>><?= Formatter::EscapeHtml($reviewer->Name) ?></option>
			<? } ?>
		</select>
	</span>
</label>

<label class="icon meter">
	<span>Status</span>
	<span>
		<select name="project-status">
			<option value="<?= Enums\ProjectStatusType::InProgress->value ?>"<? if($project->Status == Enums\ProjectStatusType::InProgress){?> selected="selected"<? } ?>>In progress</option>
			<option value="<?= Enums\ProjectStatusType::Stalled->value ?>"<? if($project->Status == Enums\ProjectStatusType::Stalled){?> selected="selected"<? } ?>>Stalled</option>
			<option value="<?= Enums\ProjectStatusType::Completed->value ?>"<? if($project->Status == Enums\ProjectStatusType::Completed){?> selected="selected"<? } ?>>Completed</option>
			<option value="<?= Enums\ProjectStatusType::Abandoned->value ?>"<? if($project->Status == Enums\ProjectStatusType::Abandoned){?> selected="selected"<? } ?>>Abandoned</option>
		</select>
	</span>
</label>

<label>
	<span>Automatically update status?</span>
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
		placeholder="https://github.com/..."
		pattern="^https:\/\/github\.com\/[^\/]+/[^\/]+/?$"
		value="<?= Formatter::EscapeHtml($project->VcsUrl ?? '') ?>"
	/>
</label>

<label>
	<span>Discussion URL</span>
	<input
		type="url"
		name="project-discussion-url"
		value="<?= Formatter::EscapeHtml($project->DiscussionUrl) ?>"
	/>
</label>
