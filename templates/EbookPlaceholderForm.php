<?
/**
 * @var Ebook $ebook
 */

$isEditForm ??= false;
$showProjectForm ??= true;
$collectionMembershipFieldCount = max(3, sizeof($ebook->CollectionMemberships));
?>

<datalist id="collection-names">
	<? foreach(Collection::GetAll() as $collection){ ?>
		<option value="<?= Formatter::EscapeHtml($collection->Name) ?>"><?= Formatter::EscapeHtml($collection->Name) ?></option>
	<? } ?>
</datalist>

<fieldset>
	<legend>Contributors</legend>
	<label class="icon user">
		<span>Author</span>
		<datalist id="author-names">
			<? foreach(Contributor::GetAllNamesByMarcRole(Enums\MarcRole::Author) as $author){ ?>
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
<details<? if( (isset($ebook->Authors) && sizeof($ebook->Authors) > 1) || (isset($ebook->Translators) && sizeof($ebook->Translators) > 0) ){ ?> open="open"<? } ?>>
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
		<label class="icon language">
			<span>Translator</span>
			<datalist id="translator-names">
				<? foreach(Contributor::GetAllNamesByMarcRole(Enums\MarcRole::Translator) as $translator){ ?>
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
		<label class="icon language">
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
		       value="<?= Formatter::EscapeHtml($ebook->Title ?? '') ?>" autocomplete="off"/>
	</label>
	<label class="icon year">
		Year published
		<input
			type="text"
			name="ebook-placeholder-year-published"
			inputmode="numeric"
			pattern="^[0-9]{1,4}$"
			autocomplete="off"
			value="<?= Formatter::EscapeHtml((string)($ebook->EbookPlaceholder?->YearPublished)) ?>"
		/>
	</label>
</fieldset>

<?= Template::CollectionMembershipFieldset(index: 1, collectionMembership: $ebook->CollectionMemberships[0] ?? null) ?>

<details<? if(sizeof($ebook->CollectionMemberships) > 1){ ?> open="open"<? } ?>>
	<summary>Additional collections</summary>
	<? for($collectionMembershipNumber = 2; $collectionMembershipNumber <= $collectionMembershipFieldCount; $collectionMembershipNumber++){ ?>
		<?= Template::CollectionMembershipFieldset(index: $collectionMembershipNumber, collectionMembership: $ebook->CollectionMemberships[$collectionMembershipNumber - 1] ?? null) ?>
	<? } ?>
	<? if(sizeof($ebook->CollectionMemberships) > 2){ ?>
		<?= Template::CollectionMembershipFieldset(index: sizeof($ebook->CollectionMemberships) + 1, collectionMembership: null) ?>
	<? } ?>
</details>
<? if(!$isEditForm && $showProjectForm){ ?>
	<fieldset>
		<legend>Project</legend>
		<label class="controls-following-fieldset">
			<span>In progress</span>
			<input type="hidden" name="ebook-placeholder-is-in-progress" value="false" />
			<input
				type="checkbox"
				name="ebook-placeholder-is-in-progress"
				<? if($ebook->EbookPlaceholder?->IsInProgress){ ?>checked="checked"<? } ?>
			/>
		</label>
		<fieldset class="project-form">
			<?= Template::ProjectForm(project: $ebook->ProjectInProgress ?? new Project(), areFieldsRequired: false) ?>
		</fieldset>
	</fieldset>
<? } ?>
<fieldset>
	<legend>Wanted list</legend>
	<label class="controls-following-fieldset">
		<span>On the wanted list</span>
		<input type="hidden" name="ebook-placeholder-is-wanted" value="false" />
		<input
			type="checkbox"
			name="ebook-placeholder-is-wanted"
			<? if($ebook->EbookPlaceholder?->IsWanted){ ?>checked="checked"<? } ?>
		/>
	</label>
	<fieldset>
		<label>
			<span>A Patron requested this book</span>
			<input type="hidden" name="ebook-is-patron-selection" value="false" />
			<input
				type="checkbox"
				name="ebook-is-patron-selection"
				<? if($ebook->IsPatronSelection){ ?>checked="checked"<? } ?>
			/>
		</label>
		<label class="icon meter">
			<span>Difficulty</span>
			<select name="ebook-placeholder-difficulty">
				<option value="">&#160;</option>
				<option value="<?= Enums\EbookPlaceholderDifficulty::Beginner->value ?>"<? if($ebook->EbookPlaceholder?->Difficulty == Enums\EbookPlaceholderDifficulty::Beginner){ ?> selected="selected"<? } ?>>Beginner</option>
				<option value="<?= Enums\EbookPlaceholderDifficulty::Intermediate->value ?>"<? if($ebook->EbookPlaceholder?->Difficulty == Enums\EbookPlaceholderDifficulty::Intermediate){ ?> selected="selected"<? } ?>>Intermediate</option>
				<option value="<?= Enums\EbookPlaceholderDifficulty::Advanced->value ?>"<? if($ebook->EbookPlaceholder?->Difficulty == Enums\EbookPlaceholderDifficulty::Advanced){ ?> selected="selected"<? } ?>>Advanced</option>
			</select>
		</label>
		<label>
			<span>Transcription URL</span>
			<input
				type="url"
				name="ebook-placeholder-transcription-url"
				autocomplete="off"
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
