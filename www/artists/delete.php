<?
use function Safe\session_unset;

$artist = null;

try{
	if(Session::$User === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!Session::$User->Benefits->CanReviewOwnArtwork){
		throw new Exceptions\InvalidPermissionsException();
	}

	session_start();

	$exception = HttpInput::SessionObject('exception', Exceptions\AppException::class);

	$artist = Artist::GetByUrlName(HttpInput::Str(GET, 'artist-url-name') ?? '');
	$artworkCount = count(Artwork::GetAllByArtist($artist->UrlName, Enums\ArtworkFilterType::Admin, null /* submitterUserId */));

	if($exception){
		http_response_code(Enums\HttpCode::UnprocessableContent->value);
		session_unset();
	}
}
catch(Exceptions\ArtistNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::ExitWithCode(Enums\HttpCode::Forbidden);
}
?><?= Template::Header(title: 'Delete ' . $artist->Name, css: ['/css/artwork.css']) ?>
<main>
	<section class="narrow">
		<nav class="breadcrumbs">
			<a href="<?= $artist->Url ?>"><?= $artist->Name ?></a> →
		</nav>
		<h1>Delete</h1>

		<?= Template::Error(exception: $exception) ?>

		<form method="<?= Enums\HttpMethod::Post->value ?>" action="/artists/<?= $artist->UrlName ?>">
			<input type="hidden" name="_method" value="<?= Enums\HttpMethod::Delete->value ?>" />
			<p>Are you sure you want to permanently delete <?= Formatter::EscapeHtml($artist->Name) ?>?</p>
			<label class="icon user">
				<span>Canonical Artist</span>
				<span>Reassign artwork by <?= Formatter::EscapeHtml($artist->Name) ?> to this artist. Total artworks to reassign: <?= $artworkCount ?></span>
				<datalist id="artist-names-except-this-artist">
					<? foreach(Artist::GetAll() as $a){ ?>
						<? if($a->ArtistId == $artist->ArtistId){ continue; } ?>
						<option value="<?= Formatter::EscapeHtml($a->Name) ?>"><?= Formatter::EscapeHtml($a->Name) ?>, d. <? if($a->DeathYear !== null){ ?><?= $a->DeathYear ?><? }else{ ?>unknown<? } ?></option>
						<? foreach(($a->AlternateNames ?? []) as $alternateName){ ?>
							<option value="<?= Formatter::EscapeHtml($alternateName) ?>"><?= Formatter::EscapeHtml($alternateName) ?>, d. <? if($a->DeathYear !== null){ ?><?= Formatter::EscapeHtml((string)$a->DeathYear) ?><? }else{ ?>unknown<? } ?></option>
						<? } ?>
					<? } ?>
				</datalist>
				<input
					type="text"
					name="canonical-artist-name"
					list="artist-names-except-this-artist"
					required="required"
				/>
			</label>

			<label>
				<input
					type="checkbox"
					name="add-alternate-name" />
				<span>Add <?= Formatter::EscapeHtml($artist->Name) ?> as an alternate name (A.K.A.) to the canonical artist</span>
			</label>

			<div class="footer">
				<button class="delete">Delete and Reassign</button>
			</div>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
