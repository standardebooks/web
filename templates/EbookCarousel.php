<?
/**
 * @var array<Ebook> $carousel
 */

$isMultiSize = $isMultiSize ?? false;
?>
<? if(sizeof($carousel) > 0){ ?>
	<ul class="ebook-carousel<? if($isMultiSize){ ?> multi-size<? } ?>">
		<? foreach($carousel as $carouselEbook){ ?>
			<li>
				<a href="<?= $carouselEbook->Url ?>">
					<picture>
						<? if($carouselEbook->CoverImage2xAvifUrl !== null){ ?><source srcset="<?= $carouselEbook->CoverImage2xAvifUrl ?> 2x, <?= $carouselEbook->CoverImageAvifUrl ?> 1x" type="image/avif"/><? } ?>
						<source srcset="<?= $carouselEbook->CoverImage2xUrl ?> 2x, <?= $carouselEbook->CoverImageUrl ?> 1x" type="image/jpg"/>
						<img src="<?= $carouselEbook->CoverImageUrl ?>" alt="<?= Formatter::EscapeHtml(strip_tags($carouselEbook->TitleWithCreditsHtml)) ?>" height="200" width="134" loading="lazy"/>
					</picture>
				</a>
			</li>
		<? } ?>
	</ul>
<? } ?>
