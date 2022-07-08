<?
require_once('Core.php');

?><?= Template::Header(['title' => 'Atom 1.0 Ebook Feeds', 'description' => 'A list of available Atom 1.0 feeds of Standard Ebooks ebooks.']) ?>
<main>
	<section class="narrow">
		<h1>Atom 1.0 Ebook Feeds</h1>
		<p>Atom feeds can be read by one of the many <a href="https://en.wikipedia.org/wiki/Comparison_of_feed_aggregators">RSS clients</a> available for download, like <a href="https://www.thunderbird.net/en-US/">Thunderbird</a>. They contain more information than regular <a href="/feeds/rss">RSS feeds</a>. Most RSS clients can read both Atom and RSS feeds.</p>
		<p>Note that some RSS readers may show these feeds ordered by when an ebook was last updated, even though the feeds are ordered by when an ebook was first released. You should be able to change the sort order in your RSS reader.</p>
		<?= Template::FeedHowTo() ?>
		<section id="atom-feeds">
			<h2>Atom Feeds</h2>
			<ul class="feed">
				<li>
					<p><a href="/feeds/atom/new-releases">New releases</a> (Public)</p>
					<p class="url"><?= SITE_URL ?>/feeds/atom/new-releases</p>
					<p>The fifteen latest Standard Ebooks, most-recently-released first.</p>
				</li>
				<li>
					<p><a href="/feeds/atom/all">All ebooks</a></p>
					<p class="url"><?= SITE_URL ?>/feeds/atom/all</p>
					<p>All Standard Ebooks, most-recently-released first.</p>
				</li>
			</ul>
			<section id="atom-ebooks-by-subject">
				<h2>Ebooks by subject</h2>
				<ul class="feed">
					<? foreach(SE_SUBJECTS as $subject){ ?>
					<li>
						<p><a href="/feeds/atom/subjects/<?= Formatter::MakeUrlSafe($subject) ?>"><?= Formatter::ToPlainText($subject) ?></a></p>
						<p class="url"><?= SITE_URL ?>/feeds/atom/subjects/<?= Formatter::MakeUrlSafe($subject) ?></p>
					</li>
					<? } ?>
				</ul>
			</section>
		</section>
	</section>
</main>
<?= Template::Footer() ?>
