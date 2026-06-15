<?
/**
 * @var Project $project
 */
?>
<table class="admin-table">
	<tbody>
		<tr>
			<td class="header-cell">Title:</td>
			<td><i><a href="<?= SITE_URL ?><?= $project->Ebook->Url ?>"><?= Formatter::EscapeHtml($project->Ebook->Title) ?></a></i>, by <a href="<?= SITE_URL ?><?= $project->Ebook->AuthorsUrl ?>"><?= Formatter::EscapeHtml($project->Ebook->AuthorsString) ?></a></td>
		</tr>
		<? if($project->Status == Enums\ProjectStatusType::AwaitingReview){ ?>
			<tr>
				<td class="header-cell">Status:</td>
				<td class="awaiting-review"><?= ucfirst($project->Status->GetDisplayName()) ?></td>
			</tr>
		<? } ?>
		<? if($project->VcsUrl !== null){ ?>
			<tr>
				<td class="header-cell">Repository:</td>
				<td>
					<a href="<?= SITE_URL ?><?= Formatter::EscapeHtml($project->VcsUrl) ?>"><?= Formatter::EscapeHtml($project->VcsUrlDomain) ?></a>
				</td>
			</tr>
		<? } ?>
		<? if($project->DiscussionUrl !== null){ ?>
			<tr>
				<td class="header-cell">Discussion:</td>
				<td>
					<a href="<?= SITE_URL ?><?= Formatter::EscapeHtml($project->DiscussionUrl) ?>"><?= Formatter::EscapeHtml($project->DiscussionUrlDomain) ?></a>
				</td>
			</tr>
		<? } ?>
		<tr>
			<td class="header-cell">Producer:</td>
			<td>
				<? if($project->Producer->Email !== null){ ?>
					<a href="mailto:<?= Formatter::EscapeHtml($project->Producer->Email) ?>"><?= Formatter::EscapeHtml($project->Producer->DisplayName) ?></a>
				<? }elseif($project->DiscussionUrl !== null){ ?>
					<a href="<?= Formatter::EscapeHtml($project->DiscussionUrl) ?>"><?= Formatter::EscapeHtml($project->Producer->DisplayName) ?></a>
				<? }else{ ?>
					<?= Formatter::EscapeHtml($project->Producer->DisplayName) ?>
				<? } ?>
			</td>
		</tr>
		<tr>
			<td class="header-cell">Manager:</td>
			<td>
				<a href="<?= SITE_URL ?><?= $project->Manager->Url ?>/projects"><?= Formatter::EscapeHtml($project->Manager->DisplayName) ?></a>
			</td>
		</tr>
		<tr>
			<td class="header-cell">Reviewer:</td>
			<td>
				<a href="<?= SITE_URL ?><?= $project->Reviewer->Url ?>/projects"><?= Formatter::EscapeHtml($project->Reviewer->DisplayName) ?></a>
			</td>
		</tr>
		<tr>
			<td class="header-cell">Started on:</td>
			<td>
				<? if(intval($project->Started->format('Y')) == intval(NOW->format('Y'))){ ?>
					<?= $project->Started->format(Enums\DateTimeFormat::ShortDateWithoutYear->value) ?>
				<? }else{ ?>
					<?= $project->Started->format(Enums\DateTimeFormat::ShortDate->value) ?>
				<? } ?>
			</td>
		</tr>
	</tbody>
</table>
