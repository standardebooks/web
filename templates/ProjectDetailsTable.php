<?
use Enums\HttpMethod;

/**
 * @var Project $project
 */

$useFullyQualifiedUrls ??= false;
$showTitle ??= true;
$showArtworkStatus ??= true;
$isAdminView ??= false;
?>
<dl>
	<? if($showTitle){ ?>
		<dt>Title:</dt>
		<dd><a href="<? if($useFullyQualifiedUrls){ ?><?= SITE_URL ?><? } ?><?= $project->Ebook->Url ?>"><?= Formatter::EscapeHtml($project->Ebook->Title) ?></a></dd>
	<? } ?>
	<dt>Producer:</dt>
	<dd>
		<? if($isAdminView){ ?>
			<a href="<?= $project->Producer->Url ?>"><?= Formatter::EscapeHtml($project->Producer->DisplayName) ?></a>
		<? }elseif($project->Producer->Email !== null){ ?>
			<a href="mailto:<?= Formatter::EscapeHtml($project->Producer->Email) ?>"><?= Formatter::EscapeHtml($project->Producer->DisplayName) ?></a>
		<? }elseif($project->DiscussionUrl !== null){ ?>
			<a href="<?= Formatter::EscapeHtml($project->DiscussionUrl) ?>"><?= Formatter::EscapeHtml($project->Producer->DisplayName) ?></a>
		<? }else{ ?>
			<?= Formatter::EscapeHtml($project->Producer->DisplayName) ?>
		<? } ?>
	</dd>
	<dt>Manager:</dt>
	<dd>
		<a href="<? if($useFullyQualifiedUrls){ ?><?= SITE_URL ?><? } ?><?= $project->Manager->Url ?>/projects"><?= Formatter::EscapeHtml($project->Manager->DisplayName) ?></a>
	</dd>
	<dt>Reviewer:</dt>
	<dd>
		<a href="<? if($useFullyQualifiedUrls){ ?><?= SITE_URL ?><? } ?><?= $project->Reviewer->Url ?>/projects"><?= Formatter::EscapeHtml($project->Reviewer->DisplayName) ?></a>
	</dd>
	<dt>Started on:</dt>
	<dd>
		<? if(intval($project->Started->format('Y')) == intval(NOW->format('Y'))){ ?>
			<?= $project->Started->format(Enums\DateTimeFormat::ShortDateWithoutYear->value) ?>
		<? }else{ ?>
			<?= $project->Started->format(Enums\DateTimeFormat::ShortDate->value) ?>
		<? } ?>
	</dd>
	<dt>Last activity:</dt>
	<dd>
		<? if(intval($project->LastActivityTimestamp->format('Y')) == intval(NOW->format('Y'))){ ?>
			<?= $project->LastActivityTimestamp->format(Enums\DateTimeFormat::ShortDateWithoutYear->value) ?>
		<? }else{ ?>
			<?= $project->LastActivityTimestamp->format(Enums\DateTimeFormat::ShortDate->value) ?>
		<? } ?>
	</dd>
	<? if($project->VcsUrl !== null){ ?>
		<dt>Repository:</dt>
		<dd>
			<a href="<?= Formatter::EscapeHtml($project->VcsUrl) ?>"><?= Formatter::EscapeHtml($project->VcsUrlDomain) ?></a>
		</dd>
	<? } ?>
	<? if($project->DiscussionUrl !== null){ ?>
		<dt>Discussion:</dt>
		<dd>
			<a href="<?= Formatter::EscapeHtml($project->DiscussionUrl) ?>"><?= Formatter::EscapeHtml($project->DiscussionUrlDomain) ?></a>
		</dd>
	<? } ?>
	<? if($showArtworkStatus){ ?>
		<dt>Cover art:</dt>
		<dd>
			<? if($project->Ebook->Artwork !== null){ ?>
				<i>
					<a href="<?= $project->Ebook->Artwork->Url ?>"><?= Formatter::EscapeHtml($project->Ebook->Artwork->Name) ?></a>
				</i> (<?= ucfirst($project->Ebook->Artwork->Status->value) ?>)
			<? }else{ ?>
				<i>None.</i>
			<? } ?>
		</dd>
		<dt>Status:</dt>
		<dd>
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
						<select name="project-status">
							<option value="<?= Enums\ProjectStatusType::InProgress->value ?>"<? if($project->Status == Enums\ProjectStatusType::InProgress){?> selected="selected"<? } ?>>In progress</option>
							<option value="<?= Enums\ProjectStatusType::AwaitingReview->value ?>"<? if($project->Status == Enums\ProjectStatusType::AwaitingReview){?> selected="selected"<? } ?>>Awaiting review</option>
							<option value="<?= Enums\ProjectStatusType::Reviewed->value ?>"<? if($project->Status == Enums\ProjectStatusType::Reviewed){?> selected="selected"<? } ?>>Reviewed</option>
							<option value="<?= Enums\ProjectStatusType::Stalled->value ?>"<? if($project->Status == Enums\ProjectStatusType::Stalled){?> selected="selected"<? } ?>>Stalled</option>
							<option value="<?= Enums\ProjectStatusType::Completed->value ?>"<? if($project->Status == Enums\ProjectStatusType::Completed){?> selected="selected"<? } ?>>Completed</option>
							<option value="<?= Enums\ProjectStatusType::Abandoned->value ?>"<? if($project->Status == Enums\ProjectStatusType::Abandoned){?> selected="selected"<? } ?>>Abandoned</option>
						</select>
					</label>
					<button>Save changes</button>
				</form>
			<? }else{ ?>
				<?= ucfirst($project->Status->GetDisplayName()) ?>
			<? } ?>
		</dd>
	<? } ?>
</dl>
