<?
/**
 * @var Project $project
 * @var User $user
 */
?>
<?= Formatter::EscapeMarkdown($project->Producer->DisplayName) ?> has indicated that _[<?= Formatter::EscapeMarkdown($project->Ebook->Title) ?>](<?= Formatter::EscapeMarkdown(SITE_URL . $project->Url) ?>)_, by [<?= Formatter::EscapeMarkdown($project->Ebook->AuthorsString) ?>](<?= Formatter::EscapeMarkdown(SITE_URL . $project->Ebook->AuthorsUrl) ?>) is **ready for your review.**

If you’re unable to review this project, [email the Editor-in-Chief](mailto:<?= Formatter::EscapeMarkdown(EDITOR_IN_CHIEF_EMAIL_ADDRESS) ?>) and we’ll reassign it.

- Title: _<?= Formatter::EscapeMarkdown($project->Ebook->Title) ?>_, by <?= Formatter::EscapeMarkdown($project->Ebook->AuthorsString) ?>

- Producer: <?= Formatter::EscapeMarkdown($project->Producer->DisplayName) ?>

- Manager: <?= Formatter::EscapeMarkdown($project->Manager->DisplayName) ?>

- Reviewer: <?= Formatter::EscapeMarkdown($project->Reviewer->DisplayName) ?>

<? if($project->VcsUrl !== null){ ?>
- Repository: [<?= Formatter::EscapeHtml($project->VcsUrlDomain) ?>](<?= Formatter::EscapeMarkdown($project->VcsUrl) ?>)

<? } ?>
<? if($project->DiscussionUrl !== null){ ?>
- Discussion: [<?= Formatter::EscapeHtml($project->DiscussionUrlDomain) ?>](<?= Formatter::EscapeMarkdown($project->DiscussionUrl) ?>)

<? } ?>
- [See all of the ebook projects you’re currently assigned to.](<?= SITE_URL ?><?= $user->Url ?>/projects)

- [See all ebook projects.](<?= SITE_URL ?>/projects)
