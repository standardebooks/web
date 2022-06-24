<?
require_once('Core.php');

?><?= Template::Header(['title' => 'Atom Ebook Feeds', 'description' => 'A list of available Atom 1.0 feeds of Standard Ebooks ebooks.']) ?>
<main>
	<article>
		<h1>Atom 1.0 Feeds</h1>
		<p>Atom feeds can be read by one of the many <a href="https://en.wikipedia.org/wiki/Comparison_of_feed_aggregators">RSS clients</a> available for download, like <a href="https://www.thunderbird.net/en-US/">Thunderbird</a>. They contain more information than regular RSS feeds. Most RSS clients can read both Atom and RSS feeds.</p>
		<p>Note that some RSS readers may show these feeds ordered by when an ebook was last updated, even though the feeds are ordered by when an ebook was first released. You should be able to change the sort order in your RSS reader.</p>
		<ul class="feed">
			<li>
				<p><a href="/feeds/atom/new-releases">New releases</a></p>
				<p class="url"><?= SITE_URL ?>/feeds/atom/new-releases</p>
				<p>The thirty latest Standard Ebooks, most-recently-released first.</p>
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
	</article>
</main>
<?= Template::Footer() ?>
