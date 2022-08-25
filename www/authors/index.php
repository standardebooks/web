<? require_once('Core.php'); ?>
<?
$ebooks = Library::GetEbooks();

$authorRecords = [];
foreach($ebooks as $ebook) {
	$ebookAuthors = $ebook->Authors;

	foreach($ebookAuthors as $ebookAuthor) {
		$authorUrl = '/ebooks/' . $ebookAuthor->UrlName;
		if(!isset($authorRecords[$authorUrl])) {
			$authorRecords[$authorUrl] = [
				'author'     => $ebookAuthor,
				'url'        => $authorUrl,
				'ebookCount' => 1,
			];
		}
		else {
			$authorRecords[$authorUrl]['ebookCount']++;
		}
	}
}

usort($collections, function($a, $b) use($collator){ return $collator->compare($a->LabelSort, $b->LabelSort); });

?><?= Template::Header([ 'title' => 'Ebook Authors', 'description' => 'All of the Standard Ebook Authors' ]) ?>
<main>
	<h1>Ebook Authors</h1>
	<ul class="authors">
	<? foreach( $authorRecords as $authorRecord ) {
	$author     = $authorRecord['author'];
	$url        = $authorRecord['url'];
	$ebookCount = $authorRecord['ebookCount'];
	?>
	<li>
		<p class="author" typeof="schema:Person" property="schema:author" resource="<?= $author->AuthorsUrl ?>">
			<a href="<?= Formatter::ToPlainText(SITE_URL . $url) ?>" property="schema:url">
				<span property="schema:name"><?= Formatter::ToPlainText($author->Name) ?></span></a> - <?= $ebookCount ?> <?= $ebookCount > 1 ? "Ebooks" : "Ebook" ?>
		</p>
	</li>
	<? } ?>
	</ul>
</main>
<?= Template::Footer() ?>
