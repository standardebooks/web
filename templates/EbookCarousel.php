<?
/**
 * @var array<Ebook> $ebooks
 */

$isMultiSize ??= false;
?>
<? if(sizeof($ebooks) > 0){ ?>
	<ul class="ebook-carousel<? if($isMultiSize){ ?> multi-size<? } ?>">
		<? foreach($ebooks as $ebook){ ?>
			<li>
				<a href="<?= $ebook->Url ?>">
					<picture>
						<? if($ebook->CoverImage2xAvifUrl !== null){ ?><source srcset="<?= $ebook->CoverImage2xAvifUrl ?> 2x, <?= $ebook->CoverImageAvifUrl ?> 1x" type="image/avif"/><? } ?>
						<source srcset="<?= $ebook->CoverImage2xUrl ?> 2x, <?= $ebook->CoverImageUrl ?> 1x" type="image/jpg"/>
						<img src="<?= $ebook->CoverImageUrl ?>" alt="<?= Formatter::EscapeHtml(strip_tags($ebook->TitleWithCreditsHtml)) ?>" height="200" width="134" loading="lazy"/>
					</picture>
				</a>
			</li>
		<? } ?>
	</ul>
<? } ?>
