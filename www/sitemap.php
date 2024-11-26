<?
/**
 * GET /sitemap
 */

$ebooks = Ebook::GetAll();
$authors = Contributor::GetAllByMarcRole(Enums\MarcRole::Author);
$collections = Collection::GetAll();

header("Content-Type: application/xml");
print("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n");
?>
<urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9">
	<url>
		<loc><?= SITE_URL ?>/</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/about</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/authors</loc>
	</url>
	<? foreach($authors as $author){ ?>
		<url>
			<loc><?= SITE_URL ?><?= $author->Url ?></loc>
		</url>
	<? } ?>
	<url>
		<loc><?= SITE_URL ?>/about/accessibility</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/about/our-goals</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/about/standard-ebooks-and-the-public-domain</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/about/what-makes-standard-ebooks-different</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/bulk-downloads/authors</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/bulk-downloads/collections</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/bulk-downloads/months</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/collections</loc>
	</url>
	<? foreach($collections as $collection){ ?>
		<url>
			<loc><?= SITE_URL ?><?= $collection->Url ?></loc>
		</url>
	<? } ?>
	<url>
		<loc><?= SITE_URL ?>/contribute/a-basic-standard-ebooks-source-folder</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/contribute/collections-policy</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/contribute/producers</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/contribute/producing-an-ebook-ste-by-step</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/contribute/report-errors-upstream</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/contribute/report-errors</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/contribute/spreadsheets</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/contribute/tips-for-editors-and-proofreaders</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/contribute/uncategorized-art-resources</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/contribute/wanted-ebooks</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/contribute/how-tos/common-issues-when-working-on-public-domain-ebooks</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/contribute/how-tos/how-to-choose-and-create-a-cover-image</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/contribute/how-tos/how-to-conquer-complex-drama-formatting</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/contribute/how-tos/how-to-create-figures-for-music-scores</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/contribute/how-tos/how-to-create-svgs-from-maps-with-several-colors</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/contribute/how-tos/how-to-review-an-ebook-production-for-publication</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/contribute/how-tos/how-to-struture-and-style-large-poetic-productions</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/contribute/how-tos/things-to-look-out-for-when-proofreading</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/donate</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/ebooks</loc>
	</url>
	<? foreach($ebooks as $ebook){ ?>
		<url>
			<loc><?= SITE_URL ?><?= $ebook->Url ?></loc>
		</url>
		<url>
			<loc><?= SITE_URL ?><?= $ebook->TextUrl ?></loc>
		</url>
		<url>
			<loc><?= SITE_URL ?><?= $ebook->TextSinglePageUrl ?></loc>
		</url>
	<? } ?>
	<url>
		<loc><?= SITE_URL ?>/feeds</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/feeds/atom</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/feeds/opds</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/feeds/rss</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/help/how-to-use-our-ebooks</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/managing-your-recurring-donation</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/newsletter</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/vocab/1.0</loc>
	</url>
	<url>
		<loc><?= SITE_URL ?>/vocab/subjects</loc>
	</url>
</urlset>
