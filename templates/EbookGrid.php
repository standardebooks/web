<?
if(!isset($ebooks)){
	$ebooks = [];
}
?>
<ol>
<? foreach($ebooks as $ebook){ ?>
	<li>
		<a href="<?= $ebook->Url ?>">
			<picture>
				<source srcset="<?= $ebook->CoverImage2xAvifUrl ?> 2x, <?= $ebook->CoverImageAvifUrl ?> 1x" type="image/avif">
				<source srcset="<?= $ebook->CoverImage2xUrl ?> 2x, <?= $ebook->CoverImageUrl ?> 1x" type="image/jpg">
				<img src="<?= $ebook->CoverImage2xUrl ?>" title="<?= Formatter::ToPlainText($ebook->Title) ?>" alt="The cover for the Standard Ebooks edition of <?= Formatter::ToPlainText(strip_tags($ebook->TitleWithCreditsHtml)) ?>" />
			</picture>
		</a>
		<p><a href="<?= $ebook->Url ?>"><?= Formatter::ToPlainText($ebook->Title) ?></a></p>
		<? foreach($ebook->Authors as $author){ ?>
		<p class="author"><a href="<?= Formatter::ToPlainText($ebook->AuthorsUrl) ?>"><?= Formatter::ToPlainText($author->Name) ?></a></p>
		<? } ?>
	</li>
<? } ?>
</ol>
