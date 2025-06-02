<?
use Enums\HttpMethod;

/**
 * @var Project $project
 */

$useFullyQualifiedUrls ??= false;
$showTitle ??= true;
$showArtworkStatus ??= true;
?>
<table class="admin-table">
	<tbody>
		<? if($showTitle){ ?>
			<tr>
				<td>Title:</td>
				<td><a href="<? if($useFullyQualifiedUrls){ ?><?= SITE_URL ?><? } ?><?= $project->Ebook->Url ?>"><?= Formatter::EscapeHtml($project->Ebook->Title) ?></a></td>
			</tr>
		<? } ?>
		<tr>
			<td>Producer:</td>
			<td>
				<? if($project->ProducerEmail !== null){ ?>
					<a href="mailto:<?= Formatter::EscapeHtml($project->ProducerEmail) ?>"><?= Formatter::EscapeHtml($project->ProducerName) ?></a>
				<? }elseif($project->DiscussionUrl !== null){ ?>
					<a href="<?= Formatter::EscapeHtml($project->DiscussionUrl) ?>"><?= Formatter::EscapeHtml($project->ProducerName) ?></a>
				<? }else{ ?>
					<?= Formatter::EscapeHtml($project->ProducerName) ?>
				<? } ?>
			</td>
		</tr>
		<tr>
			<td>Manager:</td>
			<td>
				<a href="<? if($useFullyQualifiedUrls){ ?><?= SITE_URL ?><? } ?><?= $project->Manager->Url ?>/projects"><?= Formatter::EscapeHtml($project->Manager->DisplayName) ?></a>
			</td>
		</tr>
		<tr>
			<td>Reviewer:</td>
			<td>
				<a href="<? if($useFullyQualifiedUrls){ ?><?= SITE_URL ?><? } ?><?= $project->Reviewer->Url ?>/projects"><?= Formatter::EscapeHtml($project->Reviewer->DisplayName) ?></a>
			</td>
		</tr>
		<? if($project->VcsUrl !== null){ ?>
			<tr>
				<td>Repository:</td>
				<td>
					<a href="<?= Formatter::EscapeHtml($project->VcsUrl) ?>"><?= Formatter::EscapeHtml($project->VcsUrlDomain) ?></a>
				</td>
			</tr>
		<? } ?>
		<? if($project->DiscussionUrl !== null){ ?>
			<tr>
				<td>Discussion:</td>
				<td>
					<a href="<?= Formatter::EscapeHtml($project->DiscussionUrl) ?>"><?= Formatter::EscapeHtml($project->DiscussionUrlDomain) ?></a>
				</td>
			</tr>
		<? } ?>
		<? if($showArtworkStatus){ ?>
			<tr>
				<td>Cover art:</td>
				<td>
					<? if($project->Ebook->Artwork !== null){ ?>
						<i>
							<a href="<?= $project->Ebook->Artwork->Url ?>"><?= Formatter::EscapeHtml($project->Ebook->Artwork->Name) ?></a>
						</i> (<?= ucfirst($project->Ebook->Artwork->Status->value) ?>)
					<? }else{ ?>
						<i>None.</i>
					<? } ?>
				</td>
			</tr>
			<tr>
				<td>Status:</td>
				<td>
					<? if(
						Session::$User?->Benefits->CanEditProjects
						||
						$project->ManagerUserId == Session::$User?->UserId
						||
						$project->ReviewerUserId == Session::$User?->UserId
					){ ?>

						<form action="<?= $project->Url ?>" method="<?= HttpMethod::Post->value ?>" class="single-line-form">
							<input type="hidden" name="_method" value="<?= HttpMethod::Patch->value ?>" />
							<label class="icon meter">
								<span>
									<select name="project-status">
										<option value="<?= Enums\ProjectStatusType::InProgress->value ?>"<? if($project->Status == Enums\ProjectStatusType::InProgress){?> selected="selected"<? } ?>>In progress</option>
										<option value="<?= Enums\ProjectStatusType::AwaitingReview->value ?>"<? if($project->Status == Enums\ProjectStatusType::AwaitingReview){?> selected="selected"<? } ?>>Awaiting review</option>
										<option value="<?= Enums\ProjectStatusType::Reviewed->value ?>"<? if($project->Status == Enums\ProjectStatusType::Reviewed){?> selected="selected"<? } ?>>Reviewed</option>
										<option value="<?= Enums\ProjectStatusType::Stalled->value ?>"<? if($project->Status == Enums\ProjectStatusType::Stalled){?> selected="selected"<? } ?>>Stalled</option>
										<option value="<?= Enums\ProjectStatusType::Completed->value ?>"<? if($project->Status == Enums\ProjectStatusType::Completed){?> selected="selected"<? } ?>>Completed</option>
										<option value="<?= Enums\ProjectStatusType::Abandoned->value ?>"<? if($project->Status == Enums\ProjectStatusType::Abandoned){?> selected="selected"<? } ?>>Abandoned</option>
									</select>
								</span>
							</label>
							<button>Save changes</button>
						</form>
					<? }else{ ?>
						<?= ucfirst($project->Status->GetDisplayName()) ?>
					<? } ?>
				</td>
			</tr>
		<? } ?>
	</tbody>
</table>
