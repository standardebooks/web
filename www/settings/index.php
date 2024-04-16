<?
$colorScheme = $_COOKIE['color-scheme'] ?? 'auto';

?><?= Template::Header(['title' => 'Website Settings', 'description' => 'Adjust your settings for viewing the Standard Ebooks website.']) ?>
<main>
	<h1>Website Settings</h1>
	<form action="/settings" method="post">
		<label>
			<span>Color scheme</span>
			<span>
				<select name="color-scheme">
					<option value="auto"<? if($colorScheme == 'auto'){ ?> selected="selected"<? } ?>>Automatic</option>
					<option value="light"<? if($colorScheme == 'light'){ ?> selected="selected"<? } ?>>Light</option>
					<option value="dark"<? if($colorScheme == 'dark'){ ?> selected="selected"<? } ?>>Dark</option>
				</select>
			</span>
		</label>
		<button>Apply</button>
	</form>
</main>
<?= Template::Footer() ?>
