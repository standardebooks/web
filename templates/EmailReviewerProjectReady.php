<?
/**
 * @var Project $project
 * @var User $user
 */
?><?= Template::EmailHeader(hasAdminTable: true, hasLetterhead: true) ?>
<p><?= Formatter::EscapeHtml($project->Producer->DisplayName) ?> has indicated that <a href="<?= SITE_URL ?><?= $project->Url ?>"><i><?= Formatter::EscapeHtml($project->Ebook->Title) ?></i></a>, by <a href="<?= SITE_URL ?><?= $project->Ebook->AuthorsUrl ?>"><?= Formatter::EscapeHtml($project->Ebook->AuthorsString) ?></a> is <strong>ready for your review.</strong></p>
<p>If you’re unable to review this project, <a href="mailto:<?= EDITOR_IN_CHIEF_EMAIL_ADDRESS ?>">email the Editor-in-Chief</a> and we’ll reassign it.</p>
<?= Template::ProjectDetailsTable(project: $project) ?>
<ul>
	<li>
		<p>
			<a href="<?= SITE_URL ?><?= $user->Url ?>/projects">See all of the ebook projects you’re currently assigned to.</a>
		</p>
	</li>
	<li>
		<p>
			<a href="<?= SITE_URL ?>/projects">See all ebook projects.</a>
		</p>
	</li>
</ul>
<?= Template::EmailFooter(includeLinks: false) ?>
