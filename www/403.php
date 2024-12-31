<?= Template::Header(['title' => 'You Don’t Have Permission to View This Page', 'highlight' => '', 'description' => 'You don’t have permission to view this page.', 'isErrorPage' => true]) ?>
<main>
	<section class="narrow has-hero">
		<hgroup>
			<h1>You Don’t Have Permission to View This Page</h1>
			<p>This is a 403 error.</p>
		</hgroup>
		<picture data-caption="The Guard Room. David Teniers II, 1642">
			<source srcset="/images/guard-room@2x.avif 2x, /images/guard-room.avif 1x" type="image/avif"/>
			<source srcset="/images/guard-room@2x.jpg 2x, /images/guard-room.jpg 1x" type="image/jpg"/>
			<img src="/images/guard-room@2x.jpg" alt="Baroque-era guards gather around a table in a room."/>
		</picture>
		<p>Your account doesn’t have permission to view this page.</p>
		<p>If you arrived here in error, <a href="/about#editor-in-chief">contact us</a> so that we can fix it.</p>
	</section>
</main>
<?= Template::Footer() ?>
