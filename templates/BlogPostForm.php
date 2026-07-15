<?
/**
 * @var BlogPost $blogPost
 */

$userIdentifier ??= null;
$ebookIdentifiers ??= null;
$isEditForm ??= false;
?>
<label class="icon user">
	<span>Author identifier</span>
	<span>A user ID, email, UUID, or name.</span>
	<input
		type="text"
		name="blog-post-user-identifier"
		required="required"
		value="<?= Formatter::EscapeHtml($userIdentifier ?? (string)($blogPost->UserId ?? '')) ?>"
	/>
</label>
<label class="icon year">
	<span>Publish on</span>
	<span><?= SITE_TZ_STRING ?>.</span>

	<? /* `Published` is stored as UTC in the object, but must be in the `SITE_TZ` time zone for this element. */ ?>
	<input
		type="datetime-local"
		name="blog-post-published"
		required="required"
		step="1" <? /* Required to be able to set down to seconds granularity. */ ?>
		value="<?= $blogPost->Published->setTimezone(SITE_TZ)->format(Enums\DateTimeFormat::Html->value) ?>" />
</label>
<label>
	<span>Title</span>
	<span>Can contain inline HTML elements.</span>
	<input
		type="text"
		name="blog-post-title"
		maxlength="255"
		required="required"
		value="<?= Formatter::EscapeHtml($blogPost->Title ?? '') ?>"
	/>
</label>
<label>
	<span>Subtitle</span>
	<span>Can contain inline HTML elements.</span>
	<input
		type="text"
		name="blog-post-subtitle"
		maxlength="255"
		value="<?= Formatter::EscapeHtml($blogPost->Subtitle ?? '') ?>"
	/>
</label>
<label>
	<span>Description</span>
	<input
		type="text"
		name="blog-post-description"
		maxlength="1000"
		value="<?= Formatter::EscapeHtml($blogPost->Description ?? '') ?>"
	/>
</label>
<label>
	<span>Body</span>
	<span>An HTML fragment; can be empty to create a blog post that redirects to a file on the filesystem.</span>
	<textarea
		name="blog-post-body"><?= Formatter::EscapeHtml($blogPost->Body ?? '') ?></textarea>
</label>
<label>
	<span>Additional related ebooks</span>
	<span>One ebook identifier per line; in addition to any ebooks that are detected in the post body.</span>
	<textarea
		type="text"
		name="blog-post-ebook-identifiers"><?= $ebookIdentifiers ?></textarea>
</label>
<? if($isEditForm && $blogPost->ImageCacheKey !== null){ ?>
	<label>
		<input type="checkbox" name="blog-post-remove-hero-image" value="true" /> Remove hero image
	</label>
<? } ?>
<label class="icon file-image hero-image-upload">
	<span>Hero image</span>
	<span>JPG, PNG, or WebP are accepted; <?= Http::$Request->MaxPostSize / 1024 / 1024 ?>MB maximum.<? if($isEditForm && $blogPost->ImageCacheKey !== null){ ?> Leave this blank to not change the image.<? } ?></span>
	<input type="file" name="blog-post-hero-image" accept="<?= implode(',', [Enums\ImageMimeType::JPG->value, Enums\ImageMimeType::PNG->value, Enums\ImageMimeType::WEBP->value]) ?>" />
</label>
<div class="footer">
	<button>
		<? if($isEditForm){ ?>
			Save changes
		<? }else{ ?>
			Submit
		<? } ?>
	</button>
</div>
