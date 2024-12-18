<?
/**
 * @var Ebook $ebook
 */

$showAddButton = $showAddButton ?? false;
$showEditButton = $showEditButton ?? false;
?>
<section id="projects">
	<h2>Projects</h2>
	<? if($showAddButton){ ?>
		<p>
			<a href="<?= $ebook->Url ?>/projects/new">New project</a>
		</p>
	<? } ?>
	<? if(sizeof($ebook->Projects) > 0){ ?>
		<?= Template::ProjectsTable(['projects' => $ebook->Projects, 'includeTitle' => false, 'showEditButton' => $showEditButton]) ?>
	<? } ?>
</section>
