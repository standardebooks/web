<?
/**
 * @var ?Collection $collection
 * @var array<Ebook> $ebooks
 */

$view = $view ?? Enums\ViewType::Grid;
$collection = $collection ?? null;
?>
<ol class="ebooks-list<? if($view == Enums\ViewType::List){ ?>  list<? }else{ ?> grid<? } ?>"<? if($collection !== null){ ?> typeof="schema:BookSeries" about="<?= $collection->Url ?>"<? } ?>>
	<? if($collection !== null){ ?>
		<meta property="schema:name" content="<?= Formatter::EscapeHtml($collection->Name) ?>"/>
	<? } ?>
	<? foreach($ebooks as $ebook){ ?>
		<li typeof="schema:Book"<? if($collection !== null){ ?> resource="<?= $ebook->Url ?>" property="schema:hasPart"<? if($ebook->GetCollectionPosition($collection) !== null){ ?> value="<?= $ebook->GetCollectionPosition($collection) ?>"<? } ?><? }else{ ?> about="<?= $ebook->Url ?>"<? } ?><? if($ebook->EbookPlaceholder?->IsWanted){ ?> class="wanted"<? } ?>>
			<? if($collection !== null && $ebook->GetCollectionPosition($collection) !== null){ ?>
				<meta property="schema:position" content="<?= $ebook->GetCollectionPosition($collection) ?>"/>
			<? } ?>
			<div class="thumbnail-container" aria-hidden="true"><? /* We need a container in case the thumb is shorter than the description, so that the focus outline doesn't take up the whole grid space */ ?>
				<a href="<?= $ebook->Url ?>" tabindex="-1" property="schema:url"<? if($collection !== null && $ebook->GetCollectionPosition($collection) !== null){ ?> data-ordinal="<?= $ebook->GetCollectionPosition($collection) ?>"<? } ?>>
					<? if($ebook->IsPlaceholder()){ ?>
						<div class="placeholder-cover"></div><? /* Don't self-close as this changes how Chrome renders */ ?>
					<? }else{ ?>
						<picture>
							<? if($ebook->CoverImage2xAvifUrl !== null){ ?><source srcset="<?= $ebook->CoverImage2xAvifUrl ?> 2x, <?= $ebook->CoverImageAvifUrl ?> 1x" type="image/avif"/><? } ?>
							<source srcset="<?= $ebook->CoverImage2xUrl ?> 2x, <?= $ebook->CoverImageUrl ?> 1x" type="image/jpg"/>
							<img src="<?= $ebook->CoverImage2xUrl ?>" alt="" property="schema:image" height="335" width="224"/>
						</picture>
					<? } ?>
				</a>
			</div>
			<p><a href="<?= $ebook->Url ?>" property="schema:url"><span property="schema:name"><?= Formatter::EscapeHtml($ebook->Title) ?></span></a></p>
			<? if($view == Enums\ViewType::Grid){  ?>
				<? foreach($ebook->Authors as $author){ ?>
					<p class="author" typeof="schema:Person" property="schema:author" resource="<?= $ebook->AuthorsUrl ?>"><? if($author->Name != 'Anonymous'){ ?><a href="<?= Formatter::EscapeHtml(SITE_URL . $ebook->AuthorsUrl) ?>" property="schema:url"><span property="schema:name"><?= Formatter::EscapeHtml($author->Name) ?></span></a><? } ?></p>
				<? } ?>
			<? }else{ ?>
				<div>
				<? foreach($ebook->Authors as $author){ ?>
					<p class="author"><? if($author->Name != 'Anonymous'){ ?><a href="<?= Formatter::EscapeHtml($ebook->AuthorsUrl) ?>"><?= Formatter::EscapeHtml($author->Name) ?></a><? } ?></p>
				<? } ?>
				</div>
				<div class="details">
					<? if($ebook->ContributorsHtml != ''){ ?>
						<div>
							<p><?= rtrim($ebook->ContributorsHtml, '.') ?></p>
						</div>
					<? } ?>
					<? if(!$ebook->IsPlaceholder()){ ?>
						<p><?= number_format($ebook->WordCount) ?> words • <?= $ebook->ReadingEase ?> reading ease</p>
						<ul class="tags"><? foreach($ebook->Tags as $tag){ ?><li><a href="<?= $tag->Url ?>"><?= Formatter::EscapeHtml($tag->Name) ?></a></li><? } ?></ul>
					<? } ?>
				</div>
			<? } ?>
		</li>
	<? } ?>
</ol>
