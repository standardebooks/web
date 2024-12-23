<?
use function Safe\session_unset;

session_start();

$project = HttpInput::SessionObject('project', Project::class);
$exception = HttpInput::SessionObject('exception', Exceptions\AppException::class);

try{
	if($project === null){
		$project = Project::Get(HttpInput::Int(GET, 'project-id'));
	}

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditProjects){
		throw new Exceptions\InvalidPermissionsException();
	}

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
	[
		'title' => 'Edit Project for ' . $project->Ebook->Title,
		'css' => ['/css/project.css'],
		'highlight' => '',
		'description' => 'Edit the project for ' . $project->Ebook->Title
	]
) ?>
<main>
	<section class="narrow">
		<nav class="breadcrumbs">
			<a href="<?= $project->Ebook->AuthorsUrl ?>"><?= $project->Ebook->AuthorsString ?></a> →
			<a href="<?= $project->Ebook->Url ?>"><?= Formatter::EscapeHtml($project->Ebook->Title) ?></a> →
		</nav>

		<h1>Edit Project</h1>

		<?= Template::Error(['exception' => $exception]) ?>

		<form class="project-form" autocomplete="off" method="<?= Enums\HttpMethod::Post->value ?>" action="<?= $project->Url ?>" autocomplete="off">
			<input type="hidden" name="_method" value="<?= Enums\HttpMethod::Put->value ?>" />
			<?= Template::ProjectForm(['project' => $project, 'isEditForm' => true]) ?>
			<div class="footer">
				<button>Save</button>
			</div>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
