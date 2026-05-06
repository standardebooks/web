<?= Template::Header(title: 'That HTTP Method Isn’t Allowed Here', description: 'That HTTP method isn’t allowed on this resource.', isErrorPage: true) ?>
<main>
	<section class="narrow has-hero">
		<hgroup>
			<h1>That HTTP Method Isn’t Allowed Here</h1>
			<p>This is a 405 error.</p>
		</hgroup>
		<p>You tried using an HTTP method that isn’t allowed on this resource.</p>
		<p>Check the <code>allow</code> header in this response for a list of HTTP methods that are allowed on tihs resource.</p>
	</section>
</main>
<?= Template::Footer() ?>
