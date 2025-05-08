<?

use function Safe\session_start;
use function Safe\session_unset;

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

	session_start();

	$isCreated = HttpInput::Bool(SESSION, 'is-project-created') ?? false;
	$isOnlyProjectCreated = HttpInput::Bool(SESSION, 'is-only-ebook-project-created') ?? false;
	$createdProject = HttpInput::SessionObject('project', Project::class);

	if($isCreated || $isOnlyProjectCreated){
		// We got here because a `Project` was successfully submitted.
		http_response_code(Enums\HttpCode::Created->value);
		session_unset();
	}

	$inProgressProjects = Project::GetAllByStatuses([Enums\ProjectStatusType::InProgress, Enums\ProjectStatusType::AwaitingReview, Enums\ProjectStatusType::Reviewed]);
	$stalledProjects = Project::GetAllByStatus(Enums\ProjectStatusType::Stalled);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
?><?= Template::Header(
	title: 'Projects',
	css: ['/css/project.css'],
	description: 'Ebook projects currently underway at Standard Ebooks.'
) ?>
<main>
	<section>
		<h1>Projects</h1>
		<? if(Session::$User->Benefits->CanEditProjects){ ?>
			<p>
				<a href="/projects/new">New project</a>
			</p>
		<? } ?>
		<? if($createdProject !== null){ ?>
			<? if($isCreated){ ?>
				<p class="message success">Project for <a href="<?= $createdProject->Ebook->Url ?>"><?= Formatter::EscapeHtml($createdProject->Ebook->Title) ?></a> created! Manager: <a href="<?= $createdProject->Manager->Url ?>"><?= Formatter::EscapeHtml($createdProject->Manager->Name) ?></a>, reviewer: <a href="<?= $createdProject->Reviewer->Url ?>"><?= Formatter::EscapeHtml($createdProject->Reviewer->Name) ?></a>.</p>
			<? } ?>

			<? if($isOnlyProjectCreated){ ?>
				<p class="message success">An ebook placeholder <a href="<?= $createdProject->Ebook->Url ?>">already exists</a> for this ebook, but a new project was created! Manager: <a href="<?= $createdProject->Manager->Url ?>"><?= Formatter::EscapeHtml($createdProject->Manager->Name) ?></a>, reviewer: <a href="<?= $createdProject->Reviewer->Url ?>"><?= Formatter::EscapeHtml($createdProject->Reviewer->Name) ?></a>.</p>
			<? } ?>
		<? } ?>

		<section id="active">
			<h2>Active projects</h2>
			<? if(sizeof($inProgressProjects) == 0){ ?>
				<p class="empty-notice">None.</p>
			<? }else{ ?>
				<?= Template::ProjectsTable(projects: $inProgressProjects, includeStatus: false) ?>
			<? } ?>
		</section>

		<? if(sizeof($stalledProjects) > 0){ ?>
			<section id="stalled">
				<h2>Stalled projects</h2>
				<?= Template::ProjectsTable(projects: $stalledProjects, includeStatus: false) ?>
			</section>
		<? } ?>
	</section>
</main>
<?= Template::Footer() ?>
