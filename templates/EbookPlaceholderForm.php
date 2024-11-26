<?
/**
 * @var ?Ebook $ebook
 */
$ebook = $ebook ?? new Ebook();

?>
<fieldset>
	<legend>Contributors</legend>
	<label class="user">
		<span>Author</span>
		<datalist id="author-names">
			<? foreach(Contributor::GetAllAuthorNames() as $author){ ?>
				<option value="<?= Formatter::EscapeHtml($author->Name) ?>"><?= Formatter::EscapeHtml($author->Name) ?></option>
			<? } ?>
		</datalist>
		<input
			type="text"
			name="author-name"
			list="author-names"
			required="required"
		/>
	</label>
	<label class="user">
		<span>Translator</span>
		<datalist id="translator-names">
			<? foreach(Contributor::GetAllTranslatorNames() as $translator){ ?>
				<option value="<?= Formatter::EscapeHtml($translator->Name) ?>"><?= Formatter::EscapeHtml($translator->Name) ?></option>
			<? } ?>
		</datalist>
		<input
			type="text"
			name="translator-name"
			list="translator-names"
		/>
	</label>
</fieldset>
<fieldset>
	<legend>Ebook metadata</legend>
	<label>
		<span>Title</span>
		<input type="text" name="ebook-title" required="required"/>
	</label>
	<fieldset>
		<label class="year">
			Year published
			<input
				type="text"
				name="ebook-placeholder-year-published"
				inputmode="numeric"
				pattern="[0-9]{1,4}"/>
		</label>
	</fieldset>
	<label>
		<span>Collection</span>
		<datalist id="collection-names">
			<? foreach(Collection::GetAll() as $collection){ ?>
				<option value="<?= Formatter::EscapeHtml($collection->Name) ?>"><?= Formatter::EscapeHtml($collection->Name) ?></option>
			<? } ?>
		</datalist>
		<input
			type="text"
			name="collection-name"
			list="collection-names"/>
	</label>
	<fieldset>
		<label>
			<span>Number in collection</span>
			<input
				type="text"
				name="collection-membership-sequence-number"
				inputmode="numeric"
				pattern="[0-9]{1,3}"/>
		</label>
	</fieldset>
</fieldset>
<fieldset>
	<legend>Corpus details</legend>
	<label>
		<span>On the SE wanted list?</span>
		<input
			type="checkbox"
			name="ebook-placeholder-is-wanted"/>
	</label>
	<label>
		<span>Did a Patron request this book?</span>
		<input
			type="checkbox"
			name="ebook-placeholder-is-patron"/>
	</label>
	<label>
		<span>Difficulty</span>
		<span>
			<select name="ebook-placeholder-difficulty">
				<option value=""></option>
				<option value="beginner">Beginner</option>
				<option value="intermediate">Intermediate</option>
				<option value="advanced">Advanced</option>
			</select>
		</span>
	</label>
	<label>
		<span>Wanted list status</span>
		<span>
			<select name="ebook-placeholder-status">
				<option value="wanted">Wanted</option>
				<option value="in-progress">In progress</option>
			</select>
		</span>
	</label>
	<label>
		<span>Transcription URL</span>
		<input
			type="text"
			name="ebook-placeholder-transcription-url"/>
	</label>
	<label>
		<span>Wanted list notes</span>
		<span>Markdown accepted</span>
		<textarea maxlength="1024" name="ebook-placeholder-notes"></textarea>
	</label>
</fieldset>
<div class="footer">
	<button>Submit</button>
</div>
