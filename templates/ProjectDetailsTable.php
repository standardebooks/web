<?
/**
 * @var Project $project
 */

$useFullyQualifiedUrls = $useFullyQualifiedUrls ?? false;
$showTitle = $showTitle ?? true;
$showArtworkStatus = $showArtworkStatus ?? true;
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
		<? } ?>
	</tbody>
</table>
