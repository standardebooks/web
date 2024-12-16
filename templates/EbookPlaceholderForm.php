<?
$ebook = $ebook ?? new Ebook();
?>
<fieldset>
	<legend>Contributors</legend>
	<label class="icon user">
		<span>Author</span>
		<datalist id="author-names">
			<? foreach(Contributor::GetAllAuthorNames() as $author){ ?>
				<option value="<?= Formatter::EscapeHtml($author->Name) ?>"><?= Formatter::EscapeHtml($author->Name) ?></option>
			<? } ?>
		</datalist>
		<input
			type="text"
			name="author-name-1"
			list="author-names"
			required="required"
			value="<? if(isset($ebook->Authors) && sizeof($ebook->Authors) > 0){ ?><?= Formatter::EscapeHtml($ebook->Authors[0]->Name) ?><? } ?>"
		/>
	</label>
</fieldset>
<details>
	<summary>Additional contributors</summary>
	<fieldset>
		<label class="icon user">
			<span>Second author</span>
			<input
				type="text"
				name="author-name-2"
				list="author-names"
				value="<? if(isset($ebook->Authors) && sizeof($ebook->Authors) > 1){ ?><?= Formatter::EscapeHtml($ebook->Authors[1]->Name) ?><? } ?>"
			/>
		</label>
		<label class="icon user">
			<span>Third author</span>
			<input
				type="text"
				name="author-name-3"
				list="author-names"
				value="<? if(isset($ebook->Authors) && sizeof($ebook->Authors) > 2){ ?><?= Formatter::EscapeHtml($ebook->Authors[2]->Name) ?><? } ?>"
			/>
		</label>
		<label class="icon user">
			<span>Translator</span>
			<datalist id="translator-names">
				<? foreach(Contributor::GetAllTranslatorNames() as $translator){ ?>
					<option value="<?= Formatter::EscapeHtml($translator->Name) ?>"><?= Formatter::EscapeHtml($translator->Name) ?></option>
				<? } ?>
			</datalist>
			<input
				type="text"
				name="translator-name-1"
				list="translator-names"
				value="<? if(isset($ebook->Translators) && sizeof($ebook->Translators) > 0){ ?><?= Formatter::EscapeHtml($ebook->Translators[0]->Name) ?><? } ?>"
			/>
		</label>
		<label class="icon user">
			<span>Second translator</span>
			<input
				type="text"
				name="translator-name-2"
				list="translator-names"
				value="<? if(isset($ebook->Translators) && sizeof($ebook->Translators) > 1){ ?><?= Formatter::EscapeHtml($ebook->Translators[1]->Name) ?><? } ?>"
			/>
		</label>
	</fieldset>
</details>
<fieldset>
	<legend>Ebook metadata</legend>
	<label class="icon book">
		<span>Title</span>
		<input type="text" name="ebook-title" required="required"
		       value="<?= Formatter::EscapeHtml($ebook->Title ?? '') ?>"/>
	</label>
	<label class="icon year">
		Year published
		<input
			type="text"
			name="ebook-placeholder-year-published"
			inputmode="numeric"
			pattern="[0-9]{1,4}"
			value="<?= Formatter::EscapeHtml((string)($ebook->EbookPlaceholder?->YearPublished)) ?>"
		/>
	</label>
	<label class="icon collection">
		<span>Collection</span>
		<datalist id="collection-names">
			<? foreach(Collection::GetAll() as $collection){ ?>
				<option value="<?= Formatter::EscapeHtml($collection->Name) ?>"><?= Formatter::EscapeHtml($collection->Name) ?></option>
			<? } ?>
		</datalist>
		<input
			type="text"
			name="collection-name-1"
			list="collection-names"
			value="<? if(isset($ebook->CollectionMemberships)){ ?><?= Formatter::EscapeHtml($ebook->CollectionMemberships[0]->Collection->Name) ?><? } ?>"
		/>
	</label>
	<label class="icon ordered-list">
		<span>Number in collection</span>
		<input
			type="text"
			name="sequence-number-collection-name-1"
			inputmode="numeric"
			pattern="[0-9]{1,3}"
			value="<? if(isset($ebook->CollectionMemberships) && sizeof($ebook->CollectionMemberships) > 0){ ?><?= Formatter::EscapeHtml((string)$ebook->CollectionMemberships[0]->SequenceNumber) ?><? } ?>"
		/>
	</label>
</fieldset>
<details>
	<summary>Additional collections</summary>
	<fieldset>
		<label class="icon collection">
			<span>Second Collection</span>
			<input
				type="text"
				name="collection-name-2"
				list="collection-names"
				value="<? if(isset($ebook->CollectionMemberships) && sizeof($ebook->CollectionMemberships) > 1){ ?><?= Formatter::EscapeHtml($ebook->CollectionMemberships[1]->Collection->Name) ?><? } ?>"
			/>
		</label>
		<label class="icon ordered-list">
			<span>Number in collection</span>
			<input
				type="text"
				name="sequence-number-collection-name-2"
				inputmode="numeric"
				pattern="[0-9]{1,3}"
				value="<? if(isset($ebook->CollectionMemberships) && sizeof($ebook->CollectionMemberships) > 1){ ?><?= Formatter::EscapeHtml((string)$ebook->CollectionMemberships[1]->SequenceNumber) ?><? } ?>"
			/>
		</label>
	</fieldset>
	<fieldset>
		<label class="icon collection">
			<span>Third Collection</span>
			<input
				type="text"
				name="collection-name-3"
				list="collection-names"
				value="<? if(isset($ebook->CollectionMemberships) && sizeof($ebook->CollectionMemberships) > 2){ ?><?= Formatter::EscapeHtml($ebook->CollectionMemberships[2]->Collection->Name) ?><? } ?>"
			/>
		</label>
		<label class="icon ordered-list">
			<span>Number in collection</span>
			<input
				type="text"
				name="sequence-number-collection-name-3"
				inputmode="numeric"
				pattern="[0-9]{1,3}"
				value="<? if(isset($ebook->CollectionMemberships) && sizeof($ebook->CollectionMemberships) > 2){ ?><?= Formatter::EscapeHtml((string)$ebook->CollectionMemberships[2]->SequenceNumber) ?><? } ?>"
			/>
		</label>
	</fieldset>
</details>
<fieldset>
	<legend>Project</legend>
	<label class="controls-following-fieldset">
		<span>In progress?</span>
		<input type="hidden" name="ebook-placeholder-is-in-progress" value="false" />
		<input
			type="checkbox"
			name="ebook-placeholder-is-in-progress"
			<? if($ebook->EbookPlaceholder?->IsInProgress){ ?>checked="checked"<? } ?>
		/>
	</label>
	<fieldset class="project-form">
		<?= Template::ProjectForm(['project' => $ebook->ProjectInProgress]) ?>
	</fieldset>
</fieldset>
<fieldset>
	<legend>Wanted list</legend>
	<label class="controls-following-fieldset">
		<span>On the wanted list?</span>
		<input type="hidden" name="ebook-placeholder-is-wanted" value="false" />
		<input
			type="checkbox"
			name="ebook-placeholder-is-wanted"
			<? if($ebook->EbookPlaceholder?->IsWanted){ ?>checked="checked"<? } ?>
		/>
	</label>
	<fieldset>
		<label>
			<span>Did a Patron request this book?</span>
			<input type="hidden" name="ebook-placeholder-is-patron" value="false" />
			<input
				type="checkbox"
				name="ebook-placeholder-is-patron"
				<? if($ebook->EbookPlaceholder?->IsPatron){ ?>checked="checked"<? } ?>
			/>
		</label>
		<label class="icon meter">
			<span>Difficulty</span>
			<span>
				<select name="ebook-placeholder-difficulty">
					<option value=""></option>
					<option value="<?= Enums\EbookPlaceholderDifficulty::Beginner->value ?>"<? if($ebook->EbookPlaceholder?->Difficulty == Enums\EbookPlaceholderDifficulty::Beginner){ ?> selected="selected"<? } ?>>Beginner</option>
					<option value="<?= Enums\EbookPlaceholderDifficulty::Intermediate->value ?>"<? if($ebook->EbookPlaceholder?->Difficulty == Enums\EbookPlaceholderDifficulty::Intermediate){ ?> selected="selected"<? } ?>>Intermediate</option>
					<option value="<?= Enums\EbookPlaceholderDifficulty::Advanced->value ?>"<? if($ebook->EbookPlaceholder?->Difficulty == Enums\EbookPlaceholderDifficulty::Advanced){ ?> selected="selected"<? } ?>>Advanced</option>
				</select>
			</span>
		</label>
		<label>
			<span>Transcription URL</span>
			<input
				type="url"
				name="ebook-placeholder-transcription-url"
				value="<?= Formatter::EscapeHtml($ebook->EbookPlaceholder?->TranscriptionUrl) ?>"
			/>
		</label>
		<label>
			<span>Notes</span>
			<span>Markdown accepted.</span>
			<textarea maxlength="1024" name="ebook-placeholder-notes"><?= Formatter::EscapeHtml($ebook->EbookPlaceholder?->Notes) ?></textarea>
		</label>
	</fieldset>
</fieldset>
<div class="footer">
	<button>Submit</button>
</div>
