<?
$colorScheme = Enums\ColorSchemeType::tryFrom(HttpInput::Str(COOKIE, 'color-scheme') ?? Enums\ColorSchemeType::Auto->value);

?><?= Template::Header(['title' => 'Website Settings', 'description' => 'Adjust your settings for viewing the Standard Ebooks website.']) ?>
<main>
	<h1>Website Settings</h1>
	<form action="/settings" method="<?= Enums\HttpMethod::Post->value ?>">
		<input type="hidden" name="_method" value="<?= Enums\HttpMethod::Patch->value ?>" />
		<label>
			<span>Color scheme</span>
			<span>
				<select name="color-scheme">
					<option value="<?= Enums\ColorSchemeType::Auto->value ?>"<? if($colorScheme == Enums\ColorSchemeType::Auto){ ?> selected="selected"<? } ?>>Automatic</option>
					<option value="<?= Enums\ColorSchemeType::Light->value ?>"<? if($colorScheme == Enums\ColorSchemeType::Light){ ?> selected="selected"<? } ?>>Light</option>
					<option value="<?= Enums\ColorSchemeType::Dark->value ?>"<? if($colorScheme == Enums\ColorSchemeType::Dark){ ?> selected="selected"<? } ?>>Dark</option>
				</select>
			</span>
		</label>
		<button>Apply</button>
	</form>
</main>
<?= Template::Footer() ?>
