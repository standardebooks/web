<?= Template::Header(title: 'RSS 2.0 Ebook Feeds', description: 'A list of available RSS 2.0 feeds of Standard Ebooks ebooks.') ?>
<main>
	<section class="narrow">
		<h1>RSS 2.0 Ebook Feeds</h1>
		<p>RSS feeds are the predecessors of <a href="/feeds/atom">Atom feeds</a>. They contain less information than Atom feeds, but might be better supported by some news readers.</p>
		<?= Template::FeedHowTo() ?>
		<section id="general-feeds">
			<h2>General RSS feeds</h2>
			<ul class="feed">
				<li>
					<p><a href="/feeds/rss/new-releases">New releases</a> (Public)</p>
					<p class="url"><?= SITE_URL ?>/feeds/rss/new-releases</p>
					<p>The fifteen latest Standard Ebooks, most-recently-released first.</p>
				</li>
				<li>
					<p><a href="/feeds/rss/all">All ebooks</a></p>
					<p class="url"><?= SITE_URL ?>/feeds/rss/all</p>
					<p>All Standard Ebooks, most-recently-released first.</p>
				</li>
			</ul>
		</section>
		<section id="ebooks-by-collection">
			<h2>RSS feeds by topic</h2>
			<ul class="feed">
				<li>
					<p><a href="/feeds/rss/authors">Feeds by author</a></p>
				</li>
				<li>
					<p><a href="/feeds/rss/collections">Feeds by collection</a></p>
				</li>
				<li>
					<p><a href="/feeds/rss/subjects">Feeds by subject</a></p>
				</li>
			</ul>
		</section>
	</section>
</main>
<?= Template::Footer() ?>
