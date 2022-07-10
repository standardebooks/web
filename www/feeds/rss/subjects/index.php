<?
require_once('Core.php');

?><?= Template::Header(['title' => 'RSS 2.0 Ebook Feeds by Subject', 'description' => 'A list of available RSS 2.0 feeds of Standard Ebooks ebooks by subject.']) ?>
<main>
	<article>
		<h1>RSS 2.0 Ebook Feeds by Subject</h1>
		<?= Template::FeedHowTo() ?>
		<section id="ebooks-by-subject">
			<h2>Ebooks by subject</h2>
			<ul class="feed">
				<? foreach(SE_SUBJECTS as $subject){ ?>
				<li>
					<p><a href="/feeds/rss/subjects/<?= Formatter::MakeUrlSafe($subject) ?>"><?= Formatter::ToPlainText($subject) ?></a></p>
					<p class="url"><?= SITE_URL ?>/feeds/rss/subjects/<?= Formatter::MakeUrlSafe($subject) ?></p>
				</li>
				<? } ?>
			</ul>
		</section>
	</article>
</main>
<?= Template::Footer() ?>
