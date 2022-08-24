<? require_once('Core.php'); ?>
<?
$ebooks = Library::GetEbooks();

$authorRecords = [];
foreach($ebooks as $ebook) {
	$ebookAuthors = $ebook->Authors;

	foreach($ebookAuthors as $ebookAuthor) {
		if(!isset($authorRecords[$ebook->AuthorsUrl])) {
			$authorRecords[$ebook->AuthorsUrl] = [
				'author'     => $ebookAuthor,
				'url'        => $ebook->AuthorsUrl,
				'ebookCount' => 1,
			];
		}
		else {
			$authorRecords[$ebook->AuthorsUrl]['ebookCount']++;
		}
	}
}

usort($authorRecords, function($a, $b) {
	return $a['author']->Name <=> $b['author']->Name;
});

?><?= Template::Header([ 'title' => 'Ebook Authors', 'description' => 'All of the Standard Ebook Authors' ]) ?>
<main>
	<h1>Ebook Authors</h1>
	<ul class="authors">
	<? foreach( $authorRecords as $authorRecord ) {
	$author     = $authorRecord['author'];
	$ebookCount = $authorRecord['ebookCount'];
	?>
	<li>
		<p class="author" typeof="schema:Person" property="schema:author" resource="<?= $author->AuthorsUrl ?>">
			<a href="<?= Formatter::ToPlainText(SITE_URL . $ebook->AuthorsUrl) ?>" property="schema:url">
				<span property="schema:name"><?= Formatter::ToPlainText($author->Name) ?></span></a> - <?= $ebookCount ?> <?= $ebookCount > 1 ? "Ebooks" : "Ebook" ?>
		</p>
	</li>
	<? } ?>
	</ul>
</main>
<?= Template::Footer() ?>
