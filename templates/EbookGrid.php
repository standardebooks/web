<?

if($view == ''){
	$view = VIEW_GRID;
}

if(!isset($ebooks)){
	$ebooks = [];
}
?>
<ol<? if($view == VIEW_LIST){ ?> class="list"<? } ?>>
<? foreach($ebooks as $ebook){ ?>
	<li>
		<a href="<?= $ebook->Url ?>" tabindex="-1">
			<picture>
				<? if($ebook->CoverImage2xAvifUrl !== null){ ?><source srcset="<?= $ebook->CoverImage2xAvifUrl ?> 2x, <?= $ebook->CoverImageAvifUrl ?> 1x" type="image/avif"/><? } ?>
				<source srcset="<?= $ebook->CoverImage2xUrl ?> 2x, <?= $ebook->CoverImageUrl ?> 1x" type="image/jpg"/>
				<img src="<?= $ebook->CoverImage2xUrl ?>" title="<?= Formatter::ToPlainText($ebook->Title) ?>" alt="The cover for the Standard Ebooks edition of <?= Formatter::ToPlainText(strip_tags($ebook->TitleWithCreditsHtml)) ?>"/>
			</picture>
		</a>
		<p><a href="<?= $ebook->Url ?>"><?= Formatter::ToPlainText($ebook->Title) ?></a></p>
		<? if($view == VIEW_GRID){  ?>
		<? foreach($ebook->Authors as $author){ ?>
			<p class="author"><? if($author->Name != 'Anonymous'){ ?><a href="<?= Formatter::ToPlainText($ebook->AuthorsUrl) ?>"><?= Formatter::ToPlainText($author->Name) ?></a><? } ?></p>
		<? } ?>
		<? }else{ ?>
			<div>
			<? foreach($ebook->Authors as $author){ ?>
				<p class="author"><? if($author->Name != 'Anonymous'){ ?><a href="<?= Formatter::ToPlainText($ebook->AuthorsUrl) ?>"><?= Formatter::ToPlainText($author->Name) ?></a><? } ?></p>
			<? } ?>
			</div>
			<div class="details">
				<? if($ebook->ContributorsHtml !== null){ ?>
				<div>
					<p><?= rtrim($ebook->ContributorsHtml, '.') ?></p>
				</div>
				<? } ?>
				<p><?= number_format($ebook->WordCount) ?> words • <?= $ebook->ReadingEase ?> reading ease</p>
				<ul class="tags"><? foreach($ebook->Tags as $tag){ ?><li><a href="<?= $tag->Url ?>"><?= Formatter::ToPlainText($tag->Name) ?></a></li><? } ?></ul>
			</div>
		<? } ?>
	</li>
<? } ?>
</ol>
