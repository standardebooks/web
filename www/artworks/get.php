<?
use function Safe\session_unset;

session_start();

$saved = HttpInput::Bool(SESSION, 'artwork-saved', false);
$exception = $_SESSION['exception'] ?? null;

try{
	$artwork = Artwork::GetByUrl(HttpInput::Str(GET, 'artist-url-name') ?? '', HttpInput::Str(GET, 'artwork-url-name') ?? '');
	$isAdminView = $GLOBALS['User']->Benefits->CanReviewArtwork ?? false;

	// If the artwork is not approved, and we're not an admin, don't show it.
	if($artwork->Status != COVER_ARTWORK_STATUS_APPROVED && $artwork->Status != COVER_ARTWORK_STATUS_IN_USE && !$isAdminView){
		throw new Exceptions\ArtworkNotFoundException();
	}

	// We got here because an artwork was successfully submitted
	if($saved){
		session_unset();
	}

	// We got here because an artwork submission had errors and the user has to try again
	if($exception){
		http_response_code(422);
		$artwork = $_SESSION['artwork'] ?? $artwork;
		session_unset();
	}
}
catch(Exceptions\ArtworkNotFoundException){
	Template::Emit404();
}

?><?= Template::Header(['title' => $artwork->Name, 'artwork' => true]) ?>
<main class="artworks">
	<section class="narrow">
		<h1><?= Formatter::ToPlainText($artwork->Name) ?></h1>

		<?= Template::Error(['exception' => $exception]) ?>

		<? if($saved){ ?>
			<p class="message success">Artwork saved!</p>
		<? } ?>

		<a href="<?= $artwork->ImageUrl ?>">
			<picture>
				<source srcset="<?= $artwork->Thumb2xUrl ?> 2x, <?= $artwork->ThumbUrl ?> 1x" type="image/jpg"/>
				<img src="<?= $artwork->ThumbUrl ?>" alt="" property="schema:image"/>
			</picture>
		</a>

		<?= Template::ImageCopyrightNotice() ?>

		<h2>Metadata</h2>
		<table class="artwork-metadata">
			<tr>
				<td>Title</td>
				<td><i><?= Formatter::ToPlainText($artwork->Name) ?></i></td>
			</tr>
			<tr>
				<td>Artist</td>
				<td>
					<?= Formatter::ToPlainText($artwork->Artist->Name) ?><? if(sizeof($artwork->Artist->AlternateSpellings) > 0){ ?> (A.K.A. <span class="author" typeof="schema:Person" property="schema:name"><?= implode('</span>, <span class="author" typeof="schema:Person" property="schema:name">', array_map('Formatter::ToPlainText', $artwork->Artist->AlternateSpellings)) ?></span>)<? } ?><? if($artwork->Artist->DeathYear !== null){ ?> (<abbr>d.</abbr> <?= $artwork->Artist->DeathYear ?>)<? } ?>
				</td>
			</tr>
			<tr>
				<td>Year completed</td>
				<td><? if($artwork->CompletedYear === null){ ?>Unknown<? }else{ ?><? if($artwork->CompletedYearIsCirca){ ?>Circa <? } ?><?= $artwork->CompletedYear ?><? } ?></td>
			</tr>
			<tr>
				<td>Tags</td>
				<td><ul class="tags"><? foreach($artwork->Tags as $tag){ ?><li><a href="<?= $tag->Url ?>"><?= Formatter::ToPlainText($tag->Name) ?></a></li><? } ?></ul></td>
			</tr>
			<tr>
				<td>Dimensions</td>
				<td><?= $artwork->Dimensions ?></td>
			</tr>
			<tr>
				<td>Status</td>
				<td><?= Template::ArtworkStatus(['artwork' => $artwork]) ?></td>
			</tr>
			<? if($isAdminView){ ?>
				<tr>
					<td>Submitted by</td>
					<td><? if($artwork->Submitter === null){ ?>Anonymous<? }else{ ?><a href="mailto:<?= Formatter::ToPlainText($artwork->Submitter->Email) ?>"><? if($artwork->Submitter->Name !== null){ ?> <?= Formatter::ToPlainText($artwork->Submitter->Name) ?><? }else{ ?><?= Formatter::ToPlainText($artwork->Submitter->Email) ?><? } ?></a><? } ?></td>
				</tr>
				<? if($artwork->Reviewer !== null){ ?>
					<tr>
						<td>Reviewed by</td>
						<td><a href="mailto:<?= Formatter::ToPlainText($artwork->Reviewer->Email) ?>"><? if($artwork->Reviewer->Name !== null){ ?> <?= Formatter::ToPlainText($artwork->Reviewer->Name) ?><? }else{ ?><?= Formatter::ToPlainText($artwork->Reviewer->Email) ?><? } ?></a></td>
					</tr>
				<? } ?>
			<? } ?>
		</table>

		<h2>U.S. public domain proof</h2>
		<? if($artwork->MuseumUrl !== null){ ?>
			<h3>Museum page</h3>
			<p><a href="<?= Formatter::ToPlainText($artwork->MuseumUrl) ?>"><?= Formatter::ToPlainText($artwork->MuseumUrl) ?></a></p>
			<? if($artwork->Museum !== null){ ?>
				<figure class="corrected full">
					<p>Approved museum: <?= Formatter::ToPlainText($artwork->Museum->Name) ?> <code>(<?= Formatter::ToPlainText($artwork->Museum->Domain) ?>)</code></p>
				</figure>
			<? }else{ ?>
				<figure class="wrong full">
					<p>Not an approved museum.</p>
				</figure>
			<? } ?>
		<? } ?>

		<? if($artwork->PublicationYear !== null){ ?>
			<h3>Page scans</h3>
			<ul>
				<li>Year book was published: <? if($artwork->PublicationYear !== null){ ?><?= $artwork->PublicationYear ?><? }else{ ?><i>Not provided</i><? } ?></li>
				<li>Page scan of book publication year: <? if($artwork->PublicationYearPageUrl !== null){ ?><a href="<?= Formatter::ToPlainText($artwork->PublicationYearPageUrl) ?>">Link</a><? }else{ ?><i>Not provided</i><? } ?></li>
				<li>Page scan of rights statement: <? if($artwork->CopyrightPageUrl !== null){ ?><a href="<?= Formatter::ToPlainText($artwork->CopyrightPageUrl) ?>">Link</a><? }else{ ?><i>Not provided</i><? } ?></li>
				<li>Page scan of artwork: <? if($artwork->ArtworkPageUrl !== null){ ?><a href="<?= Formatter::ToPlainText($artwork->ArtworkPageUrl) ?>">Link</a><? }else{ ?><i>Not provided</i><? } ?></li>
			</ul>
		<? } ?>

		<? if($artwork->Exception !== null){ ?>
			<h3>Public domain status exception reason</h3>
			<?= Formatter::EscapeMarkdown($artwork->Exception) ?>
		<? } ?>

		<? if($artwork->Notes !== null){ ?>
			<h2>Special notes</h2>
			<?= Formatter::EscapeMarkdown($artwork->Notes) ?>
		<? } ?>

		<? if($isAdminView){ ?>
			<h2>Editor options</h2>
			<p>Review the metadata and PD proof for this artwork submission. Approve to make it available for future producers.</p>
			<form method="post" action="<?= $artwork->Url ?>">
				<input type="hidden" name="_method" value="PATCH" />
				<? if(($artwork->SubmitterUserId != $GLOBALS['User']->UserId) || $GLOBALS['User']->Benefits->CanReviewOwnArtwork){ ?>
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
				<? } ?>
				<label>
					<span>In use by</span>
					<span>Ebook file system slug, like <code>c-s-lewis_poetry</code>. If not in use, leave this blank.</span>
					<input type="text" name="artwork-ebook-www-filesystem-path" value="<?= Formatter::ToPlainText($artwork->EbookWwwFilesystemPath) ?>"/>
				</label>
				<div class="footer">
					<button>Save changes</button>
				</div>
			</form>
		<? } ?>
	</section>
</main>
<?= Template::Footer() ?>
