<?
/**
 * @var Project $project
 * @var string $role
 * @var User $user
 */
?>
You’ve been assigned a new ebook project to **<?= $role ?>**:

- Title: [<?= Formatter::EscapeMarkdown($project->Ebook->Title) ?>](<?= Formatter::EscapeMarkdown(SITE_URL . $project->Ebook->Url) ?>)

- Producer: <? if($project->ProducerEmail !== null){ ?>[<?= Formatter::EscapeMarkdown($project->ProducerName) ?>](mailto:<?= Formatter::EscapeMarkdown($project->ProducerEmail) ?>)<? }elseif($project->DiscussionUrl !== null){ ?>[<?= Formatter::EscapeMarkdown($project->ProducerName) ?>](<?= Formatter::EscapeMarkdown($project->DiscussionUrl) ?>)<? }else{ ?><?= Formatter::EscapeMarkdown($project->ProducerName) ?><? } ?>


- Manager: [<?= Formatter::EscapeMarkdown($project->Manager->DisplayName) ?>](<?= Formatter::EscapeMarkdown(SITE_URL . $project->Manager->Url . '/projects') ?>)

- Reviewer: [<?= Formatter::EscapeMarkdown($project->Reviewer->DisplayName) ?>](<?= Formatter::EscapeMarkdown(SITE_URL . $project->Reviewer->Url . '/projects') ?>)

- Repository: [GitHub](<?= Formatter::EscapeMarkdown($project->VcsUrl) ?>)

<? if($project->DiscussionUrl !== null){ ?>
- Discussion: [Google Groups](<?= Formatter::EscapeMarkdown($project->DiscussionUrl) ?>)

<? } ?>
If you’re unable to <?= $role ?> this ebook project, [email the Editor-in-Chief](mailto:<?= Formatter::EscapeMarkdown(EDITOR_IN_CHIEF_EMAIL_ADDRESS) ?>) and we’ll reassign it.

- [See all of the ebook projects you’re currently assigned to.](<?= SITE_URL ?><?= $project->Manager->Url ?>/projects)

- [See all ebook projects.](<?= SITE_URL ?>/projects)
