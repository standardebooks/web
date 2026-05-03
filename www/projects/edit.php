<?
/**
 * GET		/projects/:project-id/edit
 */

use function Safe\session_start;
use function Safe\session_unset;

try{
	session_start();

	$originalProject = Project::Get(HttpInput::Int(GET, 'project-id'));

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditProjects){
		throw new Exceptions\InvalidPermissionsException();
	}

	$exception = HttpInput::SessionObject('exception', Exceptions\AppException::class);
	$project = HttpInput::SessionObject('project', Project::class) ?? $originalProject;

	if($exception){
		http_response_code(Enums\HttpCode::UnprocessableContent->value);
		session_unset();
	}
}
catch(Exceptions\ProjectNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
?>
<?= Template::Header(
	title: 'Edit Project for ' . $originalProject->Ebook->Title,
	css: ['/css/project.css'],
	description: 'Edit the project for ' . $originalProject->Ebook->Title
) ?>
<main>
	<section class="narrow">
		<nav class="breadcrumbs" aria-label="Breadcrumbs">
			<a href="<?= $originalProject->Ebook->AuthorsUrl ?>"><?= $originalProject->Ebook->AuthorsString ?></a> →
			<a href="<?= $originalProject->Ebook->Url ?>"><?= Formatter::EscapeHtml($originalProject->Ebook->Title) ?></a> →
		</nav>

		<h1>Edit Project</h1>

		<?= Template::Error(exception: $exception) ?>

		<form class="project-form" autocomplete="off" method="<?= Enums\HttpMethod::Post->value ?>" action="<?= $originalProject->Url ?>">
			<input type="hidden" name="_method" value="<?= Enums\HttpMethod::Patch->value ?>" />
			<?= Template::ProjectForm(project: $project, isEditForm: true) ?>
			<div class="footer">
				<button>Save</button>
			</div>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
