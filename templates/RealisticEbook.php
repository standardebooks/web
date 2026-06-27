<?
/**
 * @var Ebook $ebook
 */
?>
<figure class="realistic-ebook <? if($ebook->WordCount < 100000){ ?>small<? }elseif($ebook->WordCount < 200000){ ?>medium<? }elseif($ebook->WordCount <= 300000){ ?>large<? }elseif($ebook->WordCount < 400000){ ?>xlarge<? }else{ ?>xxlarge<? } ?>">
	<picture>
		<? if(isset($ebook->CoverImage2xAvifUrl) && isset($ebook->CoverImageAvifUrl)){ ?>
			<source srcset="<?= $ebook->CoverImage2xAvifUrl ?> 2x, <?= $ebook->CoverImageAvifUrl ?> 1x" type="image/avif"/>
		<? } ?>
		<? if(isset($ebook->CoverImageUrl) && file_exists(WEB_ROOT . $ebook->CoverImageUrl)){ ?>
			<source srcset="<?= $ebook->CoverImage2xUrl ?> 2x, <?= $ebook->CoverImageUrl ?> 1x" type="image/jpeg"/>
			<img src="<?= $ebook->CoverImageUrl ?>" alt="" height="363" width="242"/>
		<? }else{ ?>
			<img src="/images/logo-spine.svg" class="no-cover" alt="" height="363" width="242"/>
		<? } ?>
	</picture>
</figure>
