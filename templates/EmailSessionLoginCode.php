<?
/** @var string $url */
?>
<?= Template::EmailHeader() ?>
<h1>Log in to Standard Ebooks</h1>
<p>Click the button below to log in to Standard Ebooks:</p>
<p class="button-row">
	<a href="<?= Formatter::EscapeHtml($url) ?>" class="button">Log in</a>
</p>
<p>This email expires in ten minutes. If you didn’t request to log in, please <a href="<?= SITE_URL ?>/about#masthead">let us know</a>.</p>
<?= Template::EmailFooter() ?>
