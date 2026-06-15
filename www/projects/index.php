<?
/**
 * GET		/projects
 */

use function Safe\session_start;
use function Safe\session_unset;

session_start();

$isCreated = Http::$Request->Session->Get('project/create/is-created', 'bool') ?? false;
$isOnlyProjectCreated = Http::$Request->Session->Get('project/create/is-only-ebook-project-created', 'bool') ?? false;
$createdProject = Http::$Request->Session->Get('project', Project::class);
$showContactInformation = Session::$User?->Benefits->CanManageProjects || Session::$User?->Benefits->CanReviewProjects || Session::$User?->Benefits->CanEditProjects;

if($isCreated || $isOnlyProjectCreated){
	// We got here because a `Project` was successfully submitted.
	http_response_code(Enums\HttpCode::Created->value);
	session_unset();
}

$projects = Project::GetAllByStatuses([Enums\ProjectStatusType::InProgress, Enums\ProjectStatusType::AwaitingReview, Enums\ProjectStatusType::Reviewed, Enums\ProjectStatusType::Stalled]);
?><?= Template::Header(
	title: 'Projects',
	css: ['/css/project.css'],
	description: 'Ebook projects currently underway at Standard Ebooks.'
) ?>
<main>
	<section class="has-hero">
		<h1>Projects</h1>
		<picture data-caption="The Iron Rolling Mill (Modern Cyclopes). Adolph Menzel, 1875">
			<source srcset="/images/the-iron-rolling-mill@2x.avif 2x, /images/the-iron-rolling-mill.avif 1x" type="image/avif"/>
			<source srcset="/images/the-iron-rolling-mill@2x.jpg 2x, /images/the-iron-rolling-mill.jpg 1x" type="image/jpg"/>
			<img src="/images/the-iron-rolling-mill@2x.jpg" alt="Voters step up to cast votes in a county poll."/>
		</picture>
		<? if(Session::$User?->Benefits->CanEditProjects){ ?>
			<ul role="menu">
				<li><a href="/projects/new">Create a project</a></li>
			</ul>
		<? } ?>
		<? if($createdProject !== null){ ?>
			<? if($isCreated){ ?>
				<p class="message success">Project for <a href="<?= $createdProject->Ebook->Url ?>"><?= Formatter::EscapeHtml($createdProject->Ebook->Title) ?></a> created! Manager: <a href="<?= $createdProject->Manager->Url ?>"><?= Formatter::EscapeHtml($createdProject->Manager->Name) ?></a>, reviewer: <a href="<?= $createdProject->Reviewer->Url ?>"><?= Formatter::EscapeHtml($createdProject->Reviewer->Name) ?></a>.</p>
			<? } ?>

			<? if($isOnlyProjectCreated){ ?>
				<p class="message success">An ebook placeholder <a href="<?= $createdProject->Ebook->Url ?>">already exists</a> for this ebook, but a new project was created! Manager: <a href="<?= $createdProject->Manager->Url ?>"><?= Formatter::EscapeHtml($createdProject->Manager->Name) ?></a>, reviewer: <a href="<?= $createdProject->Reviewer->Url ?>"><?= Formatter::EscapeHtml($createdProject->Reviewer->Name) ?></a>.</p>
			<? } ?>
		<? } ?>

		<? if(sizeof($projects) == 0){ ?>
			<p class="empty-notice">None.</p>
		<? }else{ ?>
			<?= Template::ProjectsTable(projects: $projects, showContactInformation: $showContactInformation, isAdminView: Session::$User->Benefits->CanEditCollections ?? false) ?>
		<? } ?>
		</section>
	</main>
<?= Template::Footer() ?>
