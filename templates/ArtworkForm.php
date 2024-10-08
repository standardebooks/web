<?
use Safe\DateTimeImmutable;

$artwork = $artwork ?? null;

if($artwork === null){
	$artwork = new Artwork();
	$artwork->Artist = new Artist();
}

$isEditForm = $isEditForm ?? false;

$now = new DateTimeImmutable('now', new DateTimeZone('America/Juneau')); // Latest continental US time zone
?>
<fieldset>
	<legend>Artist details</legend>
	<label class="user">
		<span>Name</span>
		<span>For existing artists, leave the year of death blank.</span>
		<datalist id="artist-names">
			<? foreach(Library::GetArtists() as $artist){ ?>
				<option value="<?= Formatter::EscapeHtml($artist->Name) ?>"><?= Formatter::EscapeHtml($artist->Name) ?>, d. <? if($artist->DeathYear !== null){ ?><?= $artist->DeathYear ?><? }else{ ?>unknown<? } ?></option>
				<? foreach($artist->AlternateNames as $alternateName){ ?>
					<option value="<?= Formatter::EscapeHtml($alternateName) ?>"><?= Formatter::EscapeHtml($alternateName) ?>, d. <? if($artist->DeathYear !== null){ ?><?= Formatter::EscapeHtml($artist->DeathYear) ?><? }else{ ?>unknown<? } ?></option>
				<? } ?>
			<? } ?>
		</datalist>
		<input
			type="text"
			name="artist-name"
			list="artist-names"
			required="required"
			value="<?= Formatter::EscapeHtml($artwork->Artist->Name) ?>"
		/>
	</label>
	<label class="year">
		<span>Year of death</span>
		<span>If circa or unknown, enter the latest possible year.</span>
		<? /* Not using <input type="number"> for now, see https://technology.blog.gov.uk/2020/02/24/why-the-gov-uk-design-system-team-changed-the-input-type-for-numbers/ */ ?>
		<input
			type="text"
			name="artist-year-of-death"
			inputmode="numeric"
			pattern="[0-9]{1,4}"
			value="<?= Formatter::EscapeHtml($artwork->Artist->DeathYear) ?>"
		/>
	</label>
</fieldset>
<fieldset>
	<legend>Artwork details</legend>
	<label class="picture">
		Name
		<input type="text" name="artwork-name" required="required"
		       value="<?= Formatter::EscapeHtml($artwork->Name) ?>"/>
	</label>
	<fieldset>
		<label class="year">
			Year of completion
			<input
				type="text"
				name="artwork-year"
				inputmode="numeric"
				pattern="[0-9]{1,4}"
				value="<?= Formatter::EscapeHtml($artwork->CompletedYear) ?>"
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
	<label class="tags">
		<span>Tags</span>
		<span>A list of comma-separated tags.</span>
		<input
			type="text"
			name="artwork-tags"
			required="required"
			value="<?= Formatter::EscapeHtml($artwork->ImplodeTags()) ?>"
		/>
	</label>
	<label>
		<span>High-resolution image</span>
		<span>jpg, bmp, png, and tiff are accepted; <?= number_format(ARTWORK_IMAGE_MINIMUM_WIDTH) ?> × <?= number_format(ARTWORK_IMAGE_MINIMUM_HEIGHT) ?> minimum; 96MB max.<? if($isEditForm){ ?> Leave this blank to not change the image.<? } ?></span>
		<input
			type="file"
			name="artwork-image"
			<? if(!$isEditForm){ ?>required="required"<? } ?>
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
				value="<?= Formatter::EscapeHtml($artwork->MuseumUrl) ?>"
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
		<label class="year">
			Year of publication
			<input
				type="text"
				name="artwork-publication-year"
				inputmode="numeric"
				pattern="[0-9]{4}"
				value="<?= Formatter::EscapeHtml($artwork->PublicationYear) ?>"
			/>
		</label>
		<label>
			<span>URL of page with year of publication</span>
			<span>Roman numerals are OK. If no year is listed, alternate proof may be found in a printed library acquisitions list that shows this book was held by the library in a certain year; enter that in the <a href="#exception">exception field</a>.</span>
			<input
				type="url"
				name="artwork-publication-year-page-url"
				value="<?= Formatter::EscapeHtml($artwork->PublicationYearPageUrl) ?>"
			/>
		</label>
		<label>
			<span>URL of page with rights statement</span>
			<span>This page must include a statement of rights, like the copyright symbol “©” or the words “copyright” or “all rights reserved.” If no such page exists, leave this blank. This page might be the same page as above. Non-English is OK; keywords in other languages include “<i lang="fr">droits</i>” and “<i lang="de">rechte vorbehalten</i>.”</span>
			<input
				type="url"
				name="artwork-copyright-page-url"
				value="<?= Formatter::EscapeHtml($artwork->CopyrightPageUrl) ?>"
			/>
		</label>
		<label>
			<span>URL of page with artwork</span>
			<span>Review the many <a href="/manual/latest/10-art-and-images#10.3.3.7.1.1">gotchas you may encounter</a> when confirming the reproduction is the exact same painting.</span>
			<input
				type="url"
				name="artwork-artwork-page-url"
				value="<?= Formatter::EscapeHtml($artwork->ArtworkPageUrl) ?>"
			/>
		</label>
	</fieldset>
	<p><strong>or</strong> a reason for a special exception:</p>
	<fieldset id="exception">
		<label>
		<span>Public domain status exception reason</span>
		<span>Markdown accepted.</span>
		<textarea maxlength="1024" name="artwork-exception"><?= Formatter::EscapeHtml($artwork->Exception) ?></textarea>
		</label>
	</fieldset>
</fieldset>
<fieldset>
	<legend>Other details</legend>
	<label>
		<span>Special notes</span>
		<span>Any notes to remember about this artwork. Markdown accepted.</span>
		<textarea maxlength="1024" name="artwork-notes"><?= Formatter::EscapeHtml($artwork->Notes) ?></textarea>
	</label>
</fieldset>
<? if($artwork->CanStatusBeChangedBy($GLOBALS['User'] ?? null) || $artwork->CanEbookUrlBeChangedBy($GLOBALS['User'] ?? null)){ ?>
<fieldset>
	<legend>Editor options</legend>
	<? if($artwork->CanStatusBeChangedBy($GLOBALS['User'] ?? null)){ ?>
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
	<? } ?>
	<? if($artwork->CanEbookUrlBeChangedBy($GLOBALS['User'] ?? null)){ ?>
		<label>
			<span>In use by</span>
			<span>The full S.E. ebook URL. If not in use, leave this blank.</span>
			<input type="url" name="artwork-ebook-url" placeholder="https://standardebooks.org/ebooks/" pattern="^https:\/\/standardebooks\.org\/ebooks/[^\/]+(\/[^\/]+)+$" value="<?= Formatter::EscapeHtml($artwork->EbookUrl) ?>"/>
		</label>
	<? } ?>
</fieldset>
<? } ?>
<div class="footer">
	<button><? if($isEditForm){ ?>Save changes<? }else{ ?>Submit<? } ?></button>
</div>
