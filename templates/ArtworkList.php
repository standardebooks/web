<?
$artworks = $artworks ?? [];
?>
<ol class="artwork-list">
<? foreach($artworks as $artwork){ ?>
	<?
		$class = '';

		if($artwork->EbookUrl !== null){
			$class .= ' in-use';
		}

		switch($artwork->Status){
			case ArtworkStatus::Unverified:
				$class .= ' unverified';
				break;

			case ArtworkStatus::Declined:
				$class .= ' declined';
				break;
		}

		$class = trim($class);
	?>
	<li<? if($class != ''){ ?> class="<?= $class ?>"<? } ?>>
		<a href="<?= $artwork->Url ?>">
			<picture>
				<source srcset="<?= $artwork->Thumb2xUrl ?> 2x, <?= $artwork->ThumbUrl ?> 1x" type="image/jpg"/>
				<img src="<?= $artwork->ThumbUrl ?>" alt="" property="schema:image"/>
			</picture>
		</a>
	</li>
<? } ?>
</ol>
