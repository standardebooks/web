<?
/**
 * @var Ebook $ebook
 */

$showAddButton = $showAddButton ?? false;
?>
<section id="projects">
	<h2>Projects</h2>
	<? if($showAddButton){ ?>
		<a href="<?= $ebook->Url ?>/projects/new">New project</a>
	<? } ?>
	<? if(sizeof($ebook->Projects) > 0){ ?>
		<?= Template::ProjectsTable(['projects' => $ebook->Projects, 'includeTitle' => false]) ?>
	<? } ?>
</section>
