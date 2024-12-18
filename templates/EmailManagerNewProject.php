<?
/**
 * @var Project $project
 * @var string $role
 * @var User $user
 */
?><?= Template::EmailHeader(['hasAdminTable' => true, 'letterhead' => true]) ?>
<p>You’ve been assigned a new ebook project to <strong><?= $role ?></strong>:</p>
<?= Template::ProjectDetailsTable(['project' => $project, 'useFullyQualifiedUrls' => true, 'showArtworkStatus' => false]) ?>
<p>If you’re unable to <?= $role ?> this ebook project, <a href="mailto:<?= EDITOR_IN_CHIEF_EMAIL_ADDRESS ?>">email the Editor-in-Chief</a> and we’ll reassign it.</p>
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
<?= Template::EmailFooter(['includeLinks' => false]) ?>
