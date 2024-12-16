<?
/**
 * @var Project $project
 * @var string $role
 */
?><?= Template::EmailHeader(['hasDataTable' => true, 'letterhead' => true]) ?>
<p>You’ve been assigned a new ebook project to <strong><?= $role ?></strong>:</p>
<table class="data-table">
	<tbody>
		<tr>
			<td>Title:</td>
			<td><a href="<?= SITE_URL ?><?= $project->Ebook->Url ?>"><?= Formatter::EscapeHtml($project->Ebook->Title) ?></a></td>
		</tr>
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
				<a href="<?= SITE_URL ?><?= $project->Manager->Url ?>/projects"><?= Formatter::EscapeHtml($project->Manager->DisplayName) ?></a>
			</td>
		</tr>
		<tr>
			<td>Reviewer:</td>
			<td>
				<a href="<?= SITE_URL ?><?= $project->Reviewer->Url ?>/projects"><?= Formatter::EscapeHtml($project->Reviewer->DisplayName) ?></a>
			</td>
		</tr>
		<tr>
			<td>Repository:</td>
			<td>
				<a href="<?= Formatter::EscapeHtml($project->VcsUrl) ?>">GitHub</a>
			</td>
		</tr>
		<? if($project->DiscussionUrl !== null){ ?>
			<tr>
				<td>Discussion:</td>
				<td>
					<a href="<?= Formatter::EscapeHtml($project->DiscussionUrl) ?>">Google Groups</a>
				</td>
			</tr>
		<? } ?>
	</tbody>
</table>
<p>If you’re unable to <?= $role ?> this ebook project, <a href="mailto:<?= EDITOR_IN_CHIEF_EMAIL_ADDRESS ?>">email the Editor-in-Chief</a> and we’ll reassign it.</p>
<ul>
	<li>
		<p>
			<a href="<?= SITE_URL ?><?= $project->Manager->Url ?>/projects">See all of the ebook projects you’re currently assigned to.</a>
		</p>
	</li>
	<li>
		<p>
			<a href="<?= SITE_URL ?>/projects">See all ebook projects.</a>
		</p>
	</li>
</ul>
<?= Template::EmailFooter(['includeLinks' => false]) ?>
