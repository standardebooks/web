<?
try{
	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(
		!Session::$User->Benefits->CanManageProjects
		&&
		!Session::$User->Benefits->CanReviewProjects
		&&
		!Session::$User->Benefits->CanEditProjects
	){
		throw new Exceptions\InvalidPermissionsException();
	}

	$inProgressProjects = Project::GetAllByStatus(Enums\ProjectStatusType::InProgress);
	$stalledProjects = Project::GetAllByStatus(Enums\ProjectStatusType::Stalled);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::Emit403();
}
?><?= Template::Header([
				'title' => 'Projects',
				'css' => ['/css/project.css'],
				'description' => 'Ebook projects currently underway at Standard Ebooks.'
			]) ?>
<main>
	<section>
		<h1>Projects</h1>
		<section id="active">
			<h2>Active projects</h2>
			<? if(sizeof($inProgressProjects) == 0){ ?>
				<p class="empty-notice">None.</p>
			<? }else{ ?>
				<?= Template::ProjectsTable(['projects' => $inProgressProjects, 'includeStatus' => false]) ?>
			<? } ?>
		</section>

		<? if(sizeof($stalledProjects) > 0){ ?>
			<section id="stalled">
				<h2>Stalled projects</h2>
				<?= Template::ProjectsTable(['projects' => $stalledProjects, 'includeStatus' => false]) ?>
			</section>
		<? } ?>
	</section>
</main>
<?= Template::Footer() ?>
