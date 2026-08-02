<?= Template::Header(title: 'RSS/Atom Ebook Feeds', description: 'A list of available Atom 1.0 feeds of Standard Ebooks ebooks.') ?>
<main>
	<section class="narrow">
		<h1>RSS/Atom Ebook Feeds</h1>
		<p>These feeds can be read by any modern <a href="https://en.wikipedia.org/wiki/Comparison_of_feed_aggregators">RSS reader</a>.</p>
		<p>Note that some RSS readers may show ebooks ordered by when they were last updated, even though the feeds are ordered by ebooks were first released. You should be able to change this sort order in your RSS reader.</p>
		<?= Template::FeedHowTo() ?>
		<section id="general-feeds">
			<h2>General RSS/Atom feeds</h2>
			<ul class="feed">
				<li>
					<p><a href="/feeds/atom/new-releases">New releases</a> (Public)</p>
					<p class="url"><?= SITE_URL ?>/feeds/atom/new-releases</p>
					<p>The fifteen latest Standard Ebooks, most-recently-released first.</p>
				</li>
				<li>
					<p><a href="/feeds/atom/all">All ebooks</a></p>
					<p class="url">
						<? if(isset(Session::$User->Email)){ ?>https://<?= rawurlencode(Session::$User->Email) ?>@<?= SITE_DOMAIN ?><? }else{ ?><?= SITE_URL ?><? } ?>/feeds/atom/all</p>
					<p>All Standard Ebooks, most-recently-released first.</p>
				</li>
			</ul>
		</section>
		<section id="feeds-by-topic">
			<h2>RSS/Atom feeds by topic</h2>
			<ul class="feed">
				<li>
					<p><a href="/feeds/atom/authors">Feeds by author</a></p>
				</li>
				<li>
					<p><a href="/feeds/atom/collections">Feeds by collection</a></p>
				</li>
				<li>
					<p><a href="/feeds/atom/subjects">Feeds by subject</a></p>
				</li>
			</ul>
		</section>
	</section>
</main>
<?= Template::Footer() ?>
