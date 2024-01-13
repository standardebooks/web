<?
use Safe\DateTime;
use function Safe\session_unset;

session_start();

$created = HttpInput::Bool(SESSION, 'artwork-created', false);
$exception = $_SESSION['exception'] ?? null;
/** @var Artwork $artwork */
$artwork = $_SESSION['artwork'] ?? null;
$now = new DateTime('now', new DateTimeZone('America/Juneau')); // Latest continental US time zone

try{
	if($GLOBALS['User'] === null){
		throw new Exceptions\LoginRequiredException();
	}

	if(!$GLOBALS['User']->Benefits->CanUploadArtwork){
		throw new Exceptions\InvalidPermissionsException();
	}

	// We got here because an artwork was successfully submitted
	if($created){
		http_response_code(201);
		$artwork = null;
		session_unset();
	}

	// We got here because an artwork submission had errors and the user has to try again
	if($exception){
		http_response_code(422);
		session_unset();
	}

	if($artwork === null){
		$artwork = new Artwork();
		$artwork->Artist = new Artist();

		if($GLOBALS['User']->Benefits->CanReviewArtwork){
			$artwork->Status = COVER_ARTWORK_STATUS_APPROVED;
		}
	}
}
catch(Exceptions\LoginRequiredException){
	Template::RedirectToLogin();
}
catch(Exceptions\InvalidPermissionsException){
	Template::Emit403(); // No permissions to submit artwork
}

?>
<?= Template::Header(
	[
		'title' => 'Submit an Artwork',
		'artwork' => true,
		'highlight' => '',
		'description' => 'Submit public domain artwork to the database for use as cover art.'
	]
) ?>
<main>
	<section class="narrow">
		<h1>Submit an Artwork</h1>

		<?= Template::Error(['exception' => $exception]) ?>

		<? if($created){ ?>
			<p class="message success">Artwork submitted!</p>
		<? } ?>

		<form method="post" action="/artworks" enctype="multipart/form-data">
			<fieldset>
				<legend>Artist details</legend>
				<label>
					<span>Name</span>
					<span>For existing artists, leave the year of death blank.</span>
					<datalist id="artist-names">
						<? foreach(Library::GetAllArtists() as $existingArtist){ ?>
							<option value="<?= Formatter::ToPlainText($existingArtist->Name) ?>"><?= Formatter::ToPlainText($existingArtist->Name) ?>, d. <? if($existingArtist->DeathYear !== null){ ?><?= $existingArtist->DeathYear ?><? }else{ ?>unknown<? } ?></option>
						<? } ?>
					</datalist>
					<input
						type="text"
						name="artist-name"
						list="artist-names"
						required="required"
						autocomplete="off"
						value="<?= Formatter::ToPlainText($artwork->Artist->Name) ?>"
					/>
				</label>
				<label>
					<span>Year of death</span>
					<span>If circa or unknown, enter the latest possible year.</span>
					<input
						type="number"
						name="artist-year-of-death"
						min="1"
						max="<?= $now->format('Y') ?>"
						value="<?= $artwork->Artist->DeathYear ?>"
					/>
				</label>
			</fieldset>
			<fieldset>
				<legend>Artwork details</legend>
				<label>
					Name
					<input type="text" name="artwork-name" required="required"
					       value="<?= Formatter::ToPlainText($artwork->Name) ?>"/>
				</label>
				<fieldset>
					<label>
						Year of completion
						<input
							type="number"
							name="artwork-year"
							min="1"
							max="<?= $now->format('Y') ?>"
							value="<?= $artwork->CompletedYear ?>"
						/>
					</label>
					<label>
						<input
							type="checkbox"
							name="artwork-year-is-circa"
							<? if($artwork->CompletedYearIsCirca){ ?>checked="checked"<? } ?>
						/> Year is circa
					</label>
				</fieldset>
				<label>
					<span>Tags</span>
					<span>A list of comma-separated tags.</span>
					<input
						type="text"
						name="artwork-tags"
						required="required"
						autocomplete="off"
						value="<?= Formatter::ToPlainText($artwork->ImplodeTags()) ?>"
					/>
				</label>
				<label>
					<span>High-resolution image</span>
					<span>jpg, bmp, png, and tiff are accepted; <?= number_format(COVER_ARTWORK_IMAGE_MINIMUM_WIDTH) ?> × <?= number_format(COVER_ARTWORK_IMAGE_MINIMUM_HEIGHT) ?> minimum; 32MB max.</span>
					<input
						type="file"
						name="artwork-image"
						required="required"
						accept="<?= implode(',', ImageMimeType::Values()) ?>"
					/>
				</label>
			</fieldset>
			<fieldset id="pd-proof">
				<legend>Proof of U.S. public domain status</legend>
				<p>See the <a href="/manual/latest/10-art-and-images#10.3.3.7">US-PD clearance section of the SEMoS</a> for details on this section.</p>
				<p>PD proof must take the form of <strong>either</strong>:</p>
				<fieldset>
					<label>
						URL of the artwork at an <a href="/manual/latest/10-art-and-images#10.3.3.7.4">approved museum</a>
						<input
							type="url"
							name="artwork-museum-url"
							autocomplete="off"
							value="<?= Formatter::ToPlainText($artwork->MuseumUrl) ?>"
						/>
					</label>
				</fieldset>
				<p><strong>or</strong> proof that the artwork was reproduced in a book published before <?= PD_STRING ?>, including the following:</p>
				<fieldset>
					<label>
						<input
							type="checkbox"
							name="artwork-is-published-in-us"
							value="true"
							<? if($artwork->IsPublishedInUs){ ?> checked="checked"<? } ?> />
						<span>This book was published in the U.S.</span>
						<span>Yes, if a U.S. city appears anywhere near the publication year or rights statement.</span>
					</label>
					<label>
						Year of publication
						<input
							type="number"
							name="artwork-publication-year"
							min="1"
							max="<?= $now->format('Y') ?>"
							value="<?= $artwork->PublicationYear ?>"
						/>
					</label>
					<label>
						<span>URL of page with year of publication</span>
						<span>Roman numerals on the page scan are OK.</span>
						<input
							type="url"
							name="artwork-publication-year-page-url"
							autocomplete="off"
							value="<?= Formatter::ToPlainText($artwork->PublicationYearPageUrl) ?>"
						/>
					</label>
					<label>
						<span>URL of page with rights statement</span>
						<span><strong>This page must include a statement of rights, like the word “copyright” or “all rights reserved.”</strong> If no such page exists, leave this blank. This page might be the same page as above. Non-English is OK; keywords in other languages include “<i>droits</i>” and “<i>rechte vorbehalten</i>.”</span>
						<input
							type="url"
							name="artwork-copyright-page-url"
							autocomplete="off"
							value="<?= Formatter::ToPlainText($artwork->CopyrightPageUrl) ?>"
						/>
					</label>
					<label>
						<span>URL of page with artwork</span>
						<span>Review the many <a href="/manual/latest/10-art-and-images#10.3.3.7.1.1">gotchas you may encounter</a> when confirming the reproduction is the exact same painting.</span>
						<input
							type="url"
							name="artwork-artwork-page-url"
							autocomplete="off"
							value="<?= Formatter::ToPlainText($artwork->ArtworkPageUrl) ?>"
						/>
					</label>
				</fieldset>
				<p><strong>or</strong> a reason for a special exception:</p>
				<fieldset>
					<label>
					<span>Public domain status exception reason</span>
					<span>Markdown accepted.</span>
					<textarea maxlength="1024" name="artwork-exception"><?= Formatter::ToPlainText($artwork->Exception) ?></textarea>
					</label>
				</fieldset>
			</fieldset>
			<fieldset>
				<legend>Other details</legend>
				<label>
					<span>Special notes</span>
					<span>Any notes to remember about this artwork. Markdown accepted.</span>
					<textarea maxlength="1024" name="artwork-notes"><?= Formatter::ToPlainText($artwork->Notes) ?></textarea>
				</label>
			</fieldset>
			<? if($GLOBALS['User']->Benefits->CanReviewArtwork){ ?>
			<fieldset>
				<legend>Reviewer options</legend>
				<label class="select">
					<span>Artwork approval status</span>
					<span>
						<select name="artwork-status">
							<option value="<?= COVER_ARTWORK_STATUS_UNVERIFIED ?>"<? if($artwork->Status == COVER_ARTWORK_STATUS_UNVERIFIED){ ?> selected="selected"<? } ?>>Unverified</option>
							<option value="<?= COVER_ARTWORK_STATUS_DECLINED ?>"<? if($artwork->Status == COVER_ARTWORK_STATUS_DECLINED){ ?> selected="selected"<? } ?>>Declined</option>
							<option value="<?= COVER_ARTWORK_STATUS_APPROVED ?>"<? if($artwork->Status == COVER_ARTWORK_STATUS_APPROVED){ ?> selected="selected"<? } ?>>Approved</option>
							<option value="<?= COVER_ARTWORK_STATUS_IN_USE ?>"<? if($artwork->Status == COVER_ARTWORK_STATUS_IN_USE){ ?> selected="selected"<? } ?>>In use</option>
						</select>
					</span>
				</label>
				<label>
					<span>In use by</span>
					<span>Ebook file system slug, like <code>c-s-lewis_poetry</code>. If not in use, leave this blank.</span>
					<input type="text" name="artwork-ebook-www-filesystem-path" value="<?= Formatter::ToPlainText($artwork->EbookWwwFilesystemPath) ?>"/>
				</label>
			</fieldset>
			<? } ?>
			<div class="footer">
				<button>Submit</button>
			</div>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
