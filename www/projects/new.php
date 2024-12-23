<?
/**
 * GET /ebooks/<ebook-identifier>/projects/new
 * GET /projects/new
 */

use function Safe\session_unset;

session_start();

$urlPath = HttpInput::Str(GET, 'ebook-url-path');
$exception = HttpInput::SessionObject('exception', Exceptions\AppException::class);
$project = HttpInput::SessionObject('project', Project::class);
$ebook = null;

try{
	if($urlPath !== null){
		// Check this first so we can output a 404 immediately if it's not found.
		$identifier = EBOOKS_IDENTIFIER_PREFIX .  trim(str_replace('.', '', $urlPath), '/'); // Contains the portion of the URL (without query string) that comes after `https://standardebooks.org/ebooks/`.

		$ebook = Ebook::GetByIdentifier($identifier);
	}

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditProjects){
		throw new Exceptions\InvalidPermissionsException();
	}

	if($exception){
		// We got here because a `Project` submission had errors and the user has to try again.
		http_response_code(Enums\HttpCode::UnprocessableContent->value);
		session_unset();
	}

	if($project === null){
		$project = new Project();
		if($ebook !== null){
			$project->Ebook = $ebook;
			$project->EbookId = $ebook->EbookId;
		}
	}
}
catch(Exceptions\EbookNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
?><?= Template::Header([
				'title' => 'New Project',
				'css' => ['/css/project.css', '/css/ebook-placeholder.css'],
				'description' => 'Add a new ebook project.'
			]) ?>
<main>
	<section class="narrow">
		<? if(isset($project->Ebook)){ ?>
			<nav class="breadcrumbs">
				<a href="<?= $project->Ebook->AuthorsUrl ?>"><?= $project->Ebook->AuthorsHtml ?></a> → <a href="<?= $project->Ebook->Url ?>"><?= Formatter::EscapeHtml($project->Ebook->Title) ?></a> →
			</nav>
		<? } ?>
		<h1>New Project</h1>

		<?= Template::Error(['exception' => $exception]) ?>

		<form action="/projects" autocomplete="off" method="<?= Enums\HttpMethod::Post->value ?>" class="project-form">
			<?= Template::ProjectForm(['project' => $project]) ?>
			<? if(!isset($project->EbookId)){ ?>
				<fieldset class="create-update-ebook-placeholder placeholder-form">
					<legend>Placeholder</legend>
					<?= Template::EbookPlaceholderForm(['ebook' => $project->Ebook ?? new Ebook(), 'showProjectForm' => false]) ?>
				</fieldset>
			<? } ?>
			<div class="footer">
				<button>Submit</button>
			</div>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
