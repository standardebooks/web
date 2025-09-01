<?
/**
 * @var array<Project> $projects
 */

$includeTitle ??= true;
$includeStatus ??= true;
$showEditButton ??= false;
$showContactInformation ??= false;
?>
<table class="data-table projects-table">
	<caption aria-hidden="true">Scroll right →</caption>
	<thead>
		<tr>
			<? if($includeTitle){ ?>
				<th scope="col">Title</th>
			<? } ?>
			<th scope="col">Producer</th>
			<th scope="col">Manager</th>
			<th scope="col">Reviewer</th>
			<th scope="col">Last activity</th>
			<? if($includeStatus){ ?>
				<th scope="col">Status</th>
			<? } ?>
			<th></th>
			<th></th>
			<? if($showEditButton){ ?>
				<th></th>
			<? } ?>
		</tr>
	</thead>
	<tbody>
		<? foreach($projects as $project){ ?>
			<tr>
				<? if($includeTitle){ ?>
					<td class="row-header">
						<a href="<?= $project->Ebook->Url ?>">
							<?= Formatter::EscapeHtml($project->Ebook->Title) ?>
							<? if($project->Ebook->Title == "Poetry" || $project->Ebook->Title == "Short Fiction" || $project->Ebook->Title == "Essays"){ ?>
								(<?= Formatter::EscapeHtml($project->Ebook->AuthorsString) ?>)
							<? } ?>
						</a>
					</td>
				<? } ?>
				<td class="producer">
					<? if($project->ProducerEmail !== null && $showContactInformation){ ?>
						<a href="mailto:<?= Formatter::EscapeHtml($project->ProducerEmail) ?>"><?= Formatter::EscapeHtml($project->ProducerName) ?></a>
					<? }elseif($project->DiscussionUrl !== null && $showContactInformation){ ?>
						<a href="<?= Formatter::EscapeHtml($project->DiscussionUrl) ?>"><?= Formatter::EscapeHtml($project->ProducerName) ?></a>
					<? }else{ ?>
						<?= Formatter::EscapeHtml($project->ProducerName) ?>
					<? } ?>
				</td>
				<td>
					<? if($showContactInformation){ ?>
						<a href="<?= $project->Manager->Url ?>/projects"><?= Formatter::EscapeHtml($project->Manager->DisplayName) ?></a>
					<? }else{ ?>
						<?= Formatter::EscapeHtml($project->Manager->DisplayName) ?>
					<? } ?>
				</td>
				<td>
					<? if($showContactInformation){ ?>
						<a href="<?= $project->Reviewer->Url ?>/projects"><?= Formatter::EscapeHtml($project->Reviewer->DisplayName) ?></a>
					<? }else{ ?>
						<?= Formatter::EscapeHtml($project->Reviewer->DisplayName) ?>
					<? } ?>
				</td>
				<td>
					<? if(intval($project->LastActivityTimestamp->format('Y')) == intval(NOW->format('Y'))){ ?>
						<?= $project->LastActivityTimestamp->format(Enums\DateTimeFormat::ShortDateWithoutYear->value) ?>
					<? }else{ ?>
						<?= $project->LastActivityTimestamp->format(Enums\DateTimeFormat::ShortDate->value) ?>
					<? } ?>
				</td>
				<? if($includeStatus){ ?>
					<td class="status<? if($project->Status == Enums\ProjectStatusType::Stalled){ ?> stalled<? } ?><? if($project->Status == Enums\ProjectStatusType::AwaitingReview){ ?> awaiting-review<? } ?><? if($project->Status == Enums\ProjectStatusType::Reviewed){ ?> reviewed<? } ?>">
						<?= ucfirst($project->Status->GetDisplayName()) ?>
					</td>
				<? } ?>
				<td>
					<? if($project->VcsUrl !== null){ ?>
						<a href="<?= Formatter::EscapeHtml($project->VcsUrl) ?>">Repository</a>
					<? } ?>
				</td>
				<td>
					<? if($project->DiscussionUrl !== null){ ?>
						<a href="<?= Formatter::EscapeHtml($project->DiscussionUrl) ?>">Discussion</a>
					<? } ?>
				</td>
				<? if($showEditButton){ ?>
					<td>
						<a href="<?= $project->EditUrl ?>">Edit</a>
					</td>
				<? } ?>
			</tr>
		<? } ?>
	</tbody>
</table>
