<?
/**
 * GET		/ebook-placeholders/new
 */

use function Safe\session_start;
use function Safe\session_unset;

try{
	session_start();

	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanEditEbookPlaceholders){
		throw new Exceptions\PermissionsInvalidException();
	}

	$isCreated = Http::$Request->Session->Get('is-ebook-placeholder-created', 'bool') ?? false;
	$isOnlyProjectCreated = Http::$Request->Session->Get('is-only-ebook-project-created', 'bool') ?? false;
	$isDeleted = Http::$Request->Session->Get('is-ebook-placeholder-deleted', 'bool') ?? false;
	$exception = Http::$Request->Session->Get('exception', Exceptions\AppException::class);
	$ebook = Http::$Request->Session->Get('ebook', Ebook::class);
	$project = Http::$Request->Session->Get('project', Project::class);
	$deletedEbookTitle = '';
	$deletedEbookAuthor = '';

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
					if($collectionMembership->SequenceNumber !== null){
						$collectionMembership->SequenceNumber++;
					}
				}
			}
			else{
				$ebook = null;
			}
		}

		session_unset();
	}
	elseif($isDeleted){
		$deletedEbookTitle = Http::$Request->Session->Get('ebook-title');
		$deletedEbookAuthor = Http::$Request->Session->Get('ebook-authors');
		$ebook = null;

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
catch(Exceptions\PermissionsInvalidException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden); // No permissions to create an ebook placeholder.
}
?>
<?= Template::Header(
	title: 'Create an Ebook Placeholder',
	css: ['/css/ebook-placeholder.css', '/css/project.css'],
	description: 'Create a placeholder for an ebook not yet in the collection.'
) ?>
<main>
	<section class="narrow">
		<h1>Create an Ebook Placeholder</h1>

		<?= Template::Error(exception: $exception) ?>

		<? if(isset($createdEbook)){ ?>
			<? if($isOnlyProjectCreated){ ?>
				<p class="message success">An ebook placeholder <a href="<?= $createdEbook->Url ?>">already exists</a> for this ebook, but a new project was created!</p>
			<? }elseif($isCreated){ ?>
				<p class="message success">Ebook placeholder created: <a href="<?= $createdEbook->Url ?>"><?= Formatter::EscapeHtml($createdEbook->Title) ?></a>!</p>
			<? } ?>
		<? }elseif($isDeleted){ ?>
			<? if(isset($deletedEbookTitle) && isset($deletedEbookAuthor)){ ?>
				<p class="message success">Ebook placeholder deleted: <i><?= Formatter::EscapeHtml($deletedEbookTitle) ?></i>, by <?= Formatter::EscapeHtml($deletedEbookAuthor) ?>.</p>
			<? }else{ ?>
				<p class="message success">Ebook placeholder deleted.</p>
			<? } ?>
		<? } ?>

		<form class="create-update-ebook-placeholder" method="<?= Enums\HttpMethod::Post->value ?>" action="/ebook-placeholders" autocomplete="off">
			<?= Template::EbookPlaceholderForm(ebook: $ebook ?? new Ebook()) ?>
			<div class="footer">
				<button>Submit</button>
			</div>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
