<?
use function Safe\session_unset;

session_start();

$isSaved = HttpInput::Bool(SESSION, 'is-saved') ?? false;
/** @var ?\Exception $exception */
$exception = $_SESSION['exception'] ?? null;

try{
	$artwork = Artwork::GetByUrl(HttpInput::Str(GET, 'artist-url-name'), HttpInput::Str(GET, 'artwork-url-name'));
	$isReviewerView = $GLOBALS['User']->Benefits->CanReviewArtwork ?? false;
	$isAdminView = $GLOBALS['User']->Benefits->CanReviewOwnArtwork ?? false;

	// If the artwork is not approved, and we're not an admin or the submitter when they can edit, don't show it.
	if(
		($GLOBALS['User'] === null && $artwork->Status != ArtworkStatusType::Approved)
		||
		($GLOBALS['User'] !== null && $artwork->Status != ArtworkStatusType::Approved && $artwork->SubmitterUserId != $GLOBALS['User']->UserId && !$isReviewerView)
	){
		throw new Exceptions\InvalidPermissionsException();
	}

	// We got here because an artwork was successfully submitted
	if($isSaved){
		session_unset();
	}

	// We got here because an artwork PATCH operation had errors and the user has to try again
	if($exception){
		http_response_code(422);

		// Before we overwrite the original artwork with our new one, restore the old status,
		// because if the new status is 'approved' then it will hide the status form entirely,
		// which will be confusing.
		$oldStatus = $artwork->Status;
		/** @var Artwork $artwork */
		$artwork = $_SESSION['artwork'] ?? $artwork;
		$artwork->Status = $oldStatus;

		session_unset();
	}
}
catch(Exceptions\ArtworkNotFoundException){
	Template::Emit404();
}
catch(Exceptions\InvalidPermissionsException){
	Template::Emit403();
}

?><?= Template::Header(['title' => $artwork->Name, 'artwork' => true]) ?>
<main class="artworks">
	<section class="narrow">
		<h1><?= Formatter::EscapeHtml($artwork->Name) ?></h1>

		<?= Template::Error(['exception' => $exception]) ?>

		<? if($isSaved){ ?>
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
				<td><i><?= Formatter::EscapeHtml($artwork->Name) ?></i><? if($isAdminView){ ?> (#<?= $artwork->ArtworkId ?>)<? } ?></td>
			</tr>
			<tr>
				<td>Artist</td>
				<td>
					<a href="<?= $artwork->Artist->Url ?>"><?= Formatter::EscapeHtml($artwork->Artist->Name) ?></a><? if(sizeof($artwork->Artist->AlternateNames ?? []) > 0){ ?> (A.K.A. <span class="author" typeof="schema:Person" property="schema:name"><?= implode('</span>, <span class="author" typeof="schema:Person" property="schema:name">', array_map('Formatter::EscapeHtml', $artwork->Artist->AlternateNames)) ?></span>)<? } ?><? if($artwork->Artist->DeathYear !== null){ ?> (<abbr>d.</abbr> <?= $artwork->Artist->DeathYear ?>)<? } ?><? if($isAdminView){ ?> (#<?= $artwork->Artist->ArtistId ?>)<? } ?>
				</td>
			</tr>
			<tr>
				<td>Year completed</td>
				<td><? if($artwork->CompletedYear === null){ ?>Unknown<? }else{ ?><? if($artwork->CompletedYearIsCirca){ ?>Circa <? } ?><?= $artwork->CompletedYear ?><? } ?></td>
			</tr>
			<tr>
				<td>Tags</td>
				<td><ul class="tags"><? foreach($artwork->Tags as $tag){ ?><li><a href="<?= $tag->Url ?>"><?= Formatter::EscapeHtml($tag->Name) ?></a></li><? } ?></ul></td>
			</tr>
			<tr>
				<td>Dimensions</td>
				<td><?= $artwork->Dimensions ?></td>
			</tr>
			<tr>
				<td>Status</td>
				<td><?= Template::ArtworkStatus(['artwork' => $artwork]) ?></td>
			</tr>
			<? if($isReviewerView){ ?>
				<tr>
					<td>Submitted by</td>
					<td><? if($artwork->Submitter === null){ ?>Anonymous<? }else{ ?><a href="mailto:<?= Formatter::EscapeHtml($artwork->Submitter->Email) ?>"><? if($artwork->Submitter->Name !== null){ ?> <?= Formatter::EscapeHtml($artwork->Submitter->Name) ?><? }else{ ?><?= Formatter::EscapeHtml($artwork->Submitter->Email) ?><? } ?></a><? } ?><? if($isAdminView && $artwork->Submitter !== null){ ?> (#<?= $artwork->Submitter->UserId ?>)<? } ?></td>
				</tr>
				<? if($artwork->Reviewer !== null){ ?>
					<tr>
						<td>Reviewed by</td>
						<td><a href="mailto:<?= Formatter::EscapeHtml($artwork->Reviewer->Email) ?>"><? if($artwork->Reviewer->Name !== null){ ?> <?= Formatter::EscapeHtml($artwork->Reviewer->Name) ?><? }else{ ?><?= Formatter::EscapeHtml($artwork->Reviewer->Email) ?><? } ?></a><? if($isAdminView){ ?> (#<?= $artwork->Reviewer->UserId ?>)<? } ?></td>
					</tr>
				<? } ?>
			<? } ?>
		</table>

		<h2>U.S. public domain proof</h2>
		<? if($artwork->MuseumUrl !== null){ ?>
			<h3>Museum page</h3>
			<p><a href="<?= Formatter::EscapeHtml($artwork->MuseumUrl) ?>"><?= Formatter::EscapeHtml($artwork->MuseumUrl) ?></a></p>
			<? if($artwork->Museum !== null){ ?>
				<figure class="corrected full">
					<p>Approved museum: <?= Formatter::EscapeHtml($artwork->Museum->Name) ?> <code>(<?= Formatter::EscapeHtml($artwork->Museum->Domain) ?>)</code></p>
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
				<li>Year book was published: <?= $artwork->PublicationYear ?></li>
				<li>Page scan of book publication year: <? if($artwork->PublicationYearPageUrl !== null){ ?><a href="<?= Formatter::EscapeHtml($artwork->PublicationYearPageUrl) ?>">Link</a><? }else{ ?><i>Not provided</i><? } ?></li>
				<li>Page scan of rights statement: <? if($artwork->CopyrightPageUrl !== null){ ?><a href="<?= Formatter::EscapeHtml($artwork->CopyrightPageUrl) ?>">Link</a><? }else{ ?><i>Not provided</i><? } ?></li>
				<li>Page scan of artwork: <? if($artwork->ArtworkPageUrl !== null){ ?><a href="<?= Formatter::EscapeHtml($artwork->ArtworkPageUrl) ?>">Link</a><? }else{ ?><i>Not provided</i><? } ?></li>
			</ul>
		<? } ?>

		<? if($artwork->Exception !== null){ ?>
			<h3>Public domain status exception reason</h3>
			<?= Formatter::MarkdownToHtml($artwork->Exception) ?>
		<? } ?>

		<? if($artwork->Notes !== null){ ?>
			<h2>Special notes</h2>
			<?= Formatter::MarkdownToHtml($artwork->Notes) ?>
		<? } ?>

		<? if($artwork->CanBeEditedBy($GLOBALS['User'])){ ?>
			<h2>Edit artwork</h2>
			<p>The editor or submitter may edit this artwork before it’s approved. Once it’s approved, it can no longer be edited.</p>
			<p><a href="<?= $artwork->EditUrl ?>">Edit this artwork.</a></p>
		<? } ?>

		<? if($artwork->CanStatusBeChangedBy($GLOBALS['User']) || $artwork->CanEbookUrlBeChangedBy($GLOBALS['User'])){ ?>
			<h2>Editor options</h2>
			<? if($artwork->CanStatusBeChangedBy($GLOBALS['User'])){ ?>
				<p>Review the metadata and PD proof for this artwork submission. Approve to make it available for future producers. Once an artwork is approved, it can no longer be edited.</p>
			<? } ?>
			<form method="post" action="<?= $artwork->Url ?>">
				<input type="hidden" name="_method" value="PATCH" />
				<? if($artwork->CanStatusBeChangedBy($GLOBALS['User'])){ ?>
					<label>
						<span>Artwork approval status</span>
						<span>
							<select name="artwork-status">
								<option value="<?= ArtworkStatusType::Unverified->value ?>"<? if($artwork->Status == ArtworkStatusType::Unverified){ ?> selected="selected"<? } ?>>Unverified</option>
								<option value="<?= ArtworkStatusType::Declined->value ?>"<? if($artwork->Status == ArtworkStatusType::Declined){ ?> selected="selected"<? } ?>>Declined</option>
								<option value="<?= ArtworkStatusType::Approved->value ?>"<? if($artwork->Status == ArtworkStatusType::Approved){ ?> selected="selected"<? } ?>>Approved</option>
							</select>
						</span>
					</label>
				<? }else{ ?>
					<input type="hidden" name="artwork-status" value="<?= Formatter::EscapeHtml($artwork->Status->value ?? '') ?>" />
				<? } ?>
				<? if($artwork->CanEbookUrlBeChangedBy($GLOBALS['User'])){ ?>
					<label>
						<span>In use by</span>
						<span>The full S.E. ebook URL. If not in use, leave this blank.</span>
						<input type="url" autocomplete="off" name="artwork-ebook-url" value="<?= Formatter::EscapeHtml($artwork->EbookUrl) ?>"/>
					</label>
				<? }else{ ?>
					<input type="hidden" name="artwork-ebook-url" value="<?= Formatter::EscapeHtml($artwork->EbookUrl) ?>" />
				<? } ?>
				<div class="footer">
					<button>Save changes</button>
				</div>
			</form>
		<? } ?>
	</section>
</main>
<?= Template::Footer() ?>
