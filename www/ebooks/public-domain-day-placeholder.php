<?
use function Safe\preg_replace;

/** @var string $identifier Passed from script this is included from. */
$ebook = null;

try{
	try{
		// Attempt to read a draft ebook repo from the filesystem.
		// **Important:** The `deploy` script *does not tranfer `.git` folders,* which `Ebook::FromFilesystem()` needs to have. Therefore, use `rsync` to sync Public Domain Day ebooks including their `.git` folders.
		$ebook = Ebook::FromFilesystem(PD_DAY_DRAFT_PATH . '/' . str_replace('/', '_', preg_replace('|^' . EBOOKS_IDENTIFIER_PREFIX . '|', '', $identifier)));
	}
	catch(Exceptions\EbookNotFoundException $ex){
		// We may have ebooks listed as in progress, but no actual draft repos yet.
		// In that case, fill in the details from `PD_DAY_EBOOKS`.
		if(array_key_exists($identifier, PD_DAY_EBOOKS)){
			$ebook = new Ebook();
			$ebook->EbookId = 0;
			$ebook->Description = '';
			$ebook->LongDescription = '';

			$c = new Contributor();
			$c->Name = PD_DAY_EBOOKS[$identifier]['author'];
			$ebook->Authors = [$c];

			if(isset(PD_DAY_EBOOKS[$identifier]['translator'])){
				$c = new Contributor();
				$c->Name = PD_DAY_EBOOKS[$identifier]['translator'];
				$ebook->Translators = [$c];
			}

			$ebook->Title = PD_DAY_EBOOKS[$identifier]['title'];
			$ebook->WwwFilesystemPath = '';
			$ebook->Identifier = 'url:https://standardebooks.org/ebooks/' . $identifier;
		}
		else{
			throw $ex;
		}
	}
}
catch(Exceptions\EbookNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
?><?= Template::Header(['title' => strip_tags($ebook->TitleWithCreditsHtml) . ' - Free ebook download', 'highlight' => 'ebooks', 'description' => 'Free epub ebook download of the Standard Ebooks edition of ' . $ebook->Title . ': ' . $ebook->Description, 'canonicalUrl' => SITE_URL . $ebook->Url]) ?>
<main>
	<article class="ebook public-domain-day-placeholder">
		<header>
			<hgroup>
				<h1><?= Formatter::EscapeHtml($ebook->Title) ?></h1>
				<? foreach($ebook->Authors as $author){ ?>
					<? if($author->Name != 'Anonymous'){ ?>
						<h2>
							<a href="<?= Formatter::EscapeHtml($ebook->AuthorsUrl) ?>"><?= Formatter::EscapeHtml($author->Name) ?></a>
						</h2>
					<? } ?>
				<? } ?>
			</hgroup>
			<picture>
				<source srcset="/images/public-domain-day-placeholder-cover-hero@2x.jpg 2x, /images/public-domain-day-placeholder-cover-hero.jpg 1x" type="image/jpg"/>
				<img src="/images/public-domain-day-placeholder-cover-hero@2x.jpg" alt="" height="439" width="1318" />
			</picture>
		</header>

		<? if(sizeof($ebook->Tags) > 0){ ?>
			<aside id="reading-ease">
				<? if($ebook->ContributorsHtml != ''){ ?>
					<p><?= $ebook->ContributorsHtml ?></p>
				<? } ?>
				<? if(sizeof($ebook->CollectionMemberships) > 0){ ?>
					<? foreach($ebook->CollectionMemberships as $collectionMembership){ ?>
						<p>
							<?= Template::CollectionDescriptor(['collectionMembership' => $collectionMembership]) ?>.
						</p>
					<? } ?>
				<? } ?>
				<ul class="tags">
					<? foreach($ebook->Tags as $tag){ ?>
						<li>
							<a href="<?= $tag->Url ?>"><?= Formatter::EscapeHtml($tag->Name) ?></a>
						</li>
					<? } ?>
				</ul>
			</aside>
		<? } ?>

		<section id="description">
			<h2>Description</h2>
			<?= Template::DonationCounter() ?>
			<?= Template::DonationProgress() ?>

			<?= Template::DonationAlert() ?>

			<? if($ebook->LongDescription !== null){ ?>
				<?= $ebook->LongDescription ?>
			<? } ?>
		</section>

		<section id="read-free">
			<h2>Read free</h2>
			<p>This book will enter the public domain in the U.S. on <b>January 1, <?= PD_DAY_YEAR ?></b>.</p>
			<p>We’ve been working hard, and have this ebook prepared and ready for you to download free on January 1. Bookmark this page and come back then to read this ebook for free!</p>
		</section>
	</article>
</main>
<?= Template::Footer() ?>
