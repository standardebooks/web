<?
use function Safe\session_unset;

session_start();

$isCreated = HttpInput::Bool(SESSION, 'is-ebook-placeholder-created') ?? false;
$isOnlyProjectCreated = HttpInput::Bool(SESSION, 'is-only-ebook-project-created') ?? false;
$isDeleted = HttpInput::Bool(SESSION, 'is-ebook-placeholder-deleted') ?? false;
$exception = HttpInput::SessionObject('exception', Exceptions\AppException::class);
$ebook = HttpInput::SessionObject('ebook', Ebook::class);
$project = HttpInput::SessionObject('project', Project::class);
$deletedEbookTitle = '';

try{
	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditEbookPlaceholders){
		throw new Exceptions\InvalidPermissionsException();
	}

	if($isCreated || $isOnlyProjectCreated){
		// We got here because an `Ebook` was successfully created.
		http_response_code(Enums\HttpCode::Created->value);
		if($ebook !== null){
			$createdEbook = clone $ebook;

			if(sizeof($ebook->CollectionMemberships) > 0){
				// If the `EbookPlaceholder` we just added is part of a collection, prefill the form with the same data to make it easier to submit series.
				unset($ebook->EbookId);
				unset($ebook->Title);
				unset($ebook->ProjectInProgress);
				if($ebook->EbookPlaceholder !== null){
					$ebook->EbookPlaceholder->YearPublished = null;
					$ebook->EbookPlaceholder->IsWanted = false;
					$ebook->EbookPlaceholder->IsInProgress = false;
				}
				foreach($ebook->CollectionMemberships as $collectionMembership){
					$collectionMembership->SequenceNumber++;
				}
			}
			else{
				$ebook = null;
			}
		}

		session_unset();
	}
	elseif($isDeleted){
		if($ebook !== null){
			$deletedEbookTitle = $ebook->Title;
			$ebook = null;
		}
		session_unset();
	}
	elseif($exception){
		// We got here because an `Ebook` submission had errors and the user has to try again.
		http_response_code(Enums\HttpCode::UnprocessableContent->value);
		session_unset();
	}
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden); // No permissions to create an ebook placeholder.
}
?>
<?= Template::Header(
	[
		'title' => 'Create an Ebook Placeholder',
		'css' => ['/css/ebook-placeholder.css', '/css/project.css'],
		'highlight' => '',
		'description' => 'Create a placeholder for an ebook not yet in the collection.'
	]
) ?>
<main>
	<section class="narrow">
		<h1>Create an Ebook Placeholder</h1>

		<?= Template::Error(['exception' => $exception]) ?>

		<? if(isset($createdEbook)){ ?>
			<? if($isOnlyProjectCreated){ ?>
				<p class="message success">An ebook placeholder <a href="<?= $createdEbook->Url ?>">already exists</a> for this ebook, but a new project was created!</p>
			<? }elseif($isCreated){ ?>
				<p class="message success">Ebook placeholder created: <a href="<?= $createdEbook->Url ?>"><?= Formatter::EscapeHtml($createdEbook->Title) ?></a>!</p>
			<? } ?>
		<? }elseif($isDeleted){ ?>
			<p class="message success">Ebook placeholder deleted: <?= Formatter::EscapeHtml($deletedEbookTitle) ?></p>
		<? } ?>

		<form class="create-update-ebook-placeholder" method="<?= Enums\HttpMethod::Post->value ?>" action="/ebook-placeholders" autocomplete="off">
			<?= Template::EbookPlaceholderForm(['ebook' => $ebook]) ?>
			<div class="footer">
				<button>Submit</button>
			</div>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
