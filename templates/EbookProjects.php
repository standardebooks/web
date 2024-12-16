<?
/**
 * @var Ebook $ebook
 */
?>
<? if(sizeof($ebook->Projects) > 0){ ?>
	<section id="projects">
		<h2>Projects</h2>
		<?= Template::ProjectsTable(['projects' => $ebook->Projects, 'includeTitle' => false]) ?>
	</section>
<? } ?>
