<?php /** @noinspection PhpUndefinedMethodInspection,PhpIncludeInspection */
require_once('Core.php');

session_start();

$exception = $_SESSION['exception'] ?? null;

if ($exception) {
	http_response_code(422);
	session_unset();
}

$success = $_SESSION['successfully-submitted-artwork'] ?? null;

if ($success) {
	http_response_code(201);
	session_unset();
}

?>
<?= Template::Header(
	[
		'title' => 'Submit Artwork',
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

		<? if ($success) { ?>
			<p class="message success">
				Thank you for submitting artwork. It will be reviewed and added to the database if it is approved.
			</p>
		<? } ?>

		<form method="post" action="/artworks" enctype="multipart/form-data">
			<fieldset>
				<legend>Artist Details</legend>
				<div>
					<label>
						Artist Name
						<datalist id="artist-names">
							<?php foreach (Artist::GetAll() as $artist): ?>
								<option value="<?= $artist->Name ?>"></option>
							<?php endforeach; ?>
						</datalist>
						<input type="text" name="artist-name" list="artist-names" required="required"/>
					</label>
					<label>
						Year of Death
						<input type="number" name="artist-year-of-death" min="0" max="<?= gmdate('Y') ?>"/>
					</label>
				</div>
			</fieldset>
			<fieldset>
				<legend>Artwork Details</legend>
				<div>
					<label>
						Artwork Name
						<input type="text" name="artwork-name" required="required"/>
					</label>
					<label for="artwork-year">
						Year of Completion
						<label>
							(circa?
							<input type="checkbox" name="artwork-year-is-circa"/>)
						</label>
						<input type="number" id="artwork-year" name="artwork-year" min="0" max="<?= gmdate('Y') ?>"/>
					</label>
				</div>
				<label>
					Artwork Tags
					<input type="text" name="artwork-tags"/>
				</label>
			</fieldset>
			<fieldset id="pd-proof">
				<legend>Proof of Public Domain Status</legend>
				<fieldset>
					<div>
						<label>
							Link to page with year of publication
							<input type="url" name="pd-proof-year-of-publication-page"/>
						</label>
						<label>
							Year of publication
							<input type="number" name="pd-proof-year-of-publication" min="0" max="<?= gmdate('Y') ?>"/>
						</label>
					</div>
					<label>
						Link to page with copyright details
						<input type="url" name="pd-proof-copyright-page"/>
					</label>
					<label>
						Link to page with artwork
						<input type="url" name="pd-proof-artwork-page"/>
					</label>
				</fieldset>
				<label>
					Link to museum page
					<input type="url" name="pd-proof-museum-link"/>
				</label>
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
							accept="image/jpeg,image/png,image/gif"
						/>
					</label>
					<button>Submit</button>
				</div>
			</fieldset>
		</form>
	</section>
</main>
<?= Template::Footer() ?>
