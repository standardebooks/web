<?php /** @noinspection PhpUndefinedMethodInspection,PhpIncludeInspection */
use function Safe\gmdate;
use function Safe\session_unset;

session_start();

$successMessage = $_SESSION['success-message'] ?? null;

if($successMessage){
	http_response_code(201);
	session_unset();
}

/** @var Artwork $artwork */
$artwork = $_SESSION['artwork'] ?? new Artwork();
$artist = $artwork->Artist ?? new Artist();

$exception = $_SESSION['exception'] ?? null;

if($exception){
	http_response_code(422);
	session_unset();
}

?>
<?= Template::Header(
	[
		'title' => 'Submit Artwork',
		'artwork' => true,
		'highlight' => '',
		'description' => 'Submit public domain artwork to the database for use as cover art.'
	]
) ?>
<main>
	<section class="narrow">
		<hgroup>
			<h1>Submit Artwork</h1>
			<h2></h2>
		</hgroup>

		<?= Template::Error(['exception' => $exception]) ?>

		<? if($successMessage){ ?>
			<p class="message success">
				<?= Formatter::ToPlainText($successMessage) ?>
			</p>
		<? } ?>

		<form method="post" action="/artworks" enctype="multipart/form-data">
			<fieldset>
				<legend>Artist Details</legend>
				<div>
					<label>
						Artist Name
						<datalist id="artist-names">
							<?php foreach (Artist::GetAll() as $existingArtist): ?>
								<option value="<?= Formatter::ToPlainText($existingArtist->Name) ?>"><?= Formatter::ToPlainText($existingArtist->Name) ?>, d. <? if($existingArtist->DeathYear !== null){ ?><?= $existingArtist->DeathYear ?><? }else{ ?>(unknown)<? } ?></option>
							<?php endforeach; ?>
						</datalist>
						<input
							type="text"
							name="artist-name"
							list="artist-names"
							required="required"
							value="<?= Formatter::ToPlainText($artist->Name) ?>"
						/>
					</label>
					<label>
						Year of death
						<input
							type="number"
							name="artist-year-of-death"
							min="0"
							max="<?= gmdate('Y') ?>"
							value="<?= $artist->DeathYear ?>"
						/>
					</label>
				</div>
			</fieldset>
			<fieldset>
				<legend>Artwork Details</legend>
				<div>
					<label>
						Artwork Name
						<input type="text" name="artwork-name" required="required"
						       value="<?= Formatter::ToPlainText($artwork->Name) ?>"/>
					</label>
					<label for="artwork-year">
						Year of completion
						<label>
							(circa?
							<input
								type="checkbox"
								name="artwork-year-is-circa"
								value="<?= $artwork->CompletedYearIsCirca ?>"
							/>)
						</label>
						<input
							type="number"
							id="artwork-year"
							name="artwork-year"
							min="0"
							max="<?= gmdate('Y') ?>"
							value="<?= $artwork->CompletedYear ?>"
						/>
					</label>
				</div>
				<label>
					Artwork Tags
					<input
						type="text"
						name="artwork-tags"
						placeholder="tags, comma-separated"
						value="<?= Formatter::ToPlainText($artwork->ArtworkTagsImploded) ?>"
					/>
				</label>
			</fieldset>
			<fieldset id="pd-proof">
				<legend>Proof of Public Domain Status</legend>
				<p>PD proof must take the form of:</p>
				<fieldset>
					<label>
						Link to an <a href="/manual/latest/10-art-and-images#10.3.3.7.4">approved museum page</a>
						<input
							type="url"
							name="pd-proof-museum-url"
							value="<?= Formatter::ToPlainText($artwork->MuseumUrl) ?>"
						/>
					</label>
				</fieldset>
				<p>or direct links to page scans for <strong>all</strong> of the following:</p>
				<fieldset>
					<div>
						<label>
							Link to page with year of publication
							<input
								type="url"
								name="pd-proof-publication-year-page-url"
								value="<?= Formatter::ToPlainText($artwork->PublicationYearPageUrl) ?>"
							/>
						</label>
						<label>
							Year of publication
							<input
								type="number"
								name="pd-proof-publication-year"
								min="0"
								max="<?= gmdate('Y') ?>"
								value="<?= $artwork->PublicationYear ?>"
							/>
						</label>
					</div>
					<label>
						Link to page with copyright details (might be same link as above)
						<input
							type="url"
							name="pd-proof-copyright-page-url"
							value="<?= Formatter::ToPlainText($artwork->CopyrightPageUrl) ?>"
						/>
					</label>
					<label>
						Link to page with artwork
						<input
							type="url"
							name="pd-proof-artwork-page-url"
							value="<?= Formatter::ToPlainText($artwork->ArtworkPageUrl) ?>"
						/>
					</label>
				</fieldset>
				<p>See the <a href="/manual/latest/10-art-and-images#10.3.3.7">US-PD clearance section of the <abbr class="acronym">SEMoS</abbr></a> for details.</p>
			</fieldset>
			<fieldset>
				<legend></legend>
				<div>
					<label class="captcha" for="captcha">
						Type the letters in the <abbr class="acronym">CAPTCHA</abbr> image
						<input type="text" name="captcha" id="captcha" required="required" autocomplete="off"/>
					</label>
					<img
						src="/images/captcha"
						alt="A visual CAPTCHA."
						height="<?= CAPTCHA_IMAGE_HEIGHT ?>"
						width="<?= CAPTCHA_IMAGE_WIDTH ?>"
					/>
				</div>
			</fieldset>
			<fieldset>
				<div>
					<label for="input-color-upload" class="file-upload">
						Attach file
						<br/>
						<input
							type="file"
							name="color-upload"
							id="input-color-upload"
							required="required"
							accept="image/jpeg"
						/>
					</label>
					<button>Submit</button>
				</div>
			</fieldset>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
