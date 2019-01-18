<?
if(!isset($ebooks)){
	$ebooks = [];
}
?>
<ol>
<? foreach($ebooks as $ebook){ ?>
	<li>
		<a href="<?= $ebook->Url ?>"><img src="<?= $ebook->CoverImage2xUrl ?>" title="<?= Formatter::ToPlainText($ebook->Title) ?>" alt="The cover for the Standard Ebooks edition of <?= Formatter::ToPlainText($ebook->Title) ?>" /></a>
		<p><a href="<?= $ebook->Url ?>"><?= Formatter::ToPlainText($ebook->Title) ?></a></p>
		<? foreach($ebook->Authors as $author){ ?>
		<p class="author"><a href="<?= Formatter::ToPlainText($ebook->AuthorsUrl) ?>"><?= Formatter::ToPlainText($author->Name) ?></a></p>
		<? } ?>
	</li>
<? } ?>
</ol>
