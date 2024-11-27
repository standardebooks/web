<?
/**
 * @var Ebook $ebook
 */
?>
<figure class="realistic-ebook <? if($ebook->WordCount < 100000){ ?>small<? }elseif($ebook->WordCount < 200000){ ?>medium<? }elseif($ebook->WordCount <= 300000){ ?>large<? }elseif($ebook->WordCount < 400000){ ?>xlarge<? }else{ ?>xxlarge<? } ?>">
	<picture>
		<source srcset="<?= $ebook->CoverImage2xAvifUrl ?> 2x, <?= $ebook->CoverImageAvifUrl ?> 1x" type="image/avif"/>
		<source srcset="<?= $ebook->CoverImage2xUrl ?> 2x, <?= $ebook->CoverImageUrl ?> 1x" type="image/jpg"/>
		<img src="<?= $ebook->CoverImageUrl ?>" alt="" height="363" width="242"/>
	</picture>
</figure>
