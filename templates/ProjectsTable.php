<?
/**
 * @var array<Project> $projects
 */

$includeTitle = $includeTitle ?? true;
$includeStatus = $includeStatus ?? true;
?>
<table class="data-table">
	<caption aria-hidden="hidden">Scroll right →</caption>
	<thead>
		<tr class="mid-header">
			<? if($includeTitle){ ?>
				<th scope="col">Title</th>
			<? } ?>
			<th scope="col">Producer</th>
			<th scope="col">Manager</th>
			<th scope="col">Reviewer</th>
			<th scope="col">Started</th>
			<th scope="col">Last activity</th>
			<? if($includeStatus){ ?>
				<th scope="col">Status</th>
			<? } ?>
			<th/>
			<th/>
		</tr>
	</thead>
	<tbody>
		<? foreach($projects as $project){ ?>
			<tr>
				<? if($includeTitle){ ?>
					<td class="row-header">
						<a href="<?= $project->Ebook->Url ?>"><?= Formatter::EscapeHtml($project->Ebook->Title) ?></a>
					</td>
				<? } ?>
				<td class="producer">
					<? if($project->ProducerEmail !== null){ ?>
						<a href="mailto:<?= Formatter::EscapeHtml($project->ProducerEmail) ?>"><?= Formatter::EscapeHtml($project->ProducerName) ?></a>
					<? }elseif($project->DiscussionUrl !== null){ ?>
						<a href="<?= Formatter::EscapeHtml($project->DiscussionUrl) ?>"><?= Formatter::EscapeHtml($project->ProducerName) ?></a>
					<? }else{ ?>
						<?= Formatter::EscapeHtml($project->ProducerName) ?>
					<? } ?>
				</td>
				<td>
					<a href="<?= $project->ManagerUser->Url ?>/projects"><?= Formatter::EscapeHtml($project->ManagerUser->DisplayName) ?></a>
				</td>
				<td>
					<a href="<?= $project->ReviewerUser->Url ?>/projects"><?= Formatter::EscapeHtml($project->ReviewerUser->DisplayName) ?></a>
				</td>
				<td>
					<?= $project->Started->format(Enums\DateTimeFormat::ShortDate->value) ?>
				</td>
				<td>
					<?= $project->LastActivityTimestamp->format(Enums\DateTimeFormat::ShortDate->value) ?>
				</td>
				<? if($includeStatus){ ?>
					<td class="status<? if($project->Status == Enums\ProjectStatusType::Stalled){ ?> stalled<? } ?>">
						<?= ucfirst($project->Status->GetDisplayName()) ?>
					</td>
				<? } ?>
				<td>
					<a href="<?= Formatter::EscapeHtml($project->VcsUrl) ?>">GitHub</a>
				</td>
				<td>
					<? if($project->DiscussionUrl !== null){ ?>
						<a href="<?= Formatter::EscapeHtml($project->DiscussionUrl) ?>">Discussion</a>
					<? } ?>
				</td>
			</tr>
		<? } ?>
	</tbody>
</table>
