<?
/**
 * @var string $id
 * @var string $url
 * @var string $title
 * @var ?string $subtitle
 * @var DateTimeImmutable $updated
 * @var array<Ebook> $entries
 */

$subtitle ??= null;

// The `xslt-polyfill.min.js` script is to support the deprecation of XSLT in major browsers that occurred in 2025-2026.
//
// See:
//
// - <https://developer.chrome.com/docs/web-platform/deprecating-xslt#rss_and_atom_feeds>
//
// - <https://github.com/mfreed7/xslt_polyfill>


// Note that the XSL stylesheet gets stripped during `se clean` when we generate the feed.
// `se clean` will also start adding empty namespaces everywhere if we include the stylesheet declaration first.
// We have to add it programmatically when saving the feed file.
print("<?xml version=\"1.0\" encoding=\"utf-8\"?>\n");
?>
<feed xmlns="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/">
	<script src="<?= SITE_URL ?>/scripts/xslt-polyfill.min.js" xmlns="http://www.w3.org/1999/xhtml"></script>
	<id><?= SITE_URL . Formatter::EscapeXml($id) ?></id>
	<link href="<?= SITE_URL . Formatter::EscapeXml($url) ?>" rel="self" type="application/atom+xml"/>
	<title><?= Formatter::EscapeXml($title) ?></title>
	<? if($subtitle !== null){ ?>
		<subtitle><?= Formatter::EscapeXml($subtitle) ?></subtitle>
	<? } ?>
	<icon><?= SITE_URL ?>/images/logo.png</icon>
	<updated><?= $updated->format(Enums\DateTimeFormat::Iso->value) ?></updated>
	<author>
		<name>Standard Ebooks</name>
		<uri><?= SITE_URL ?></uri>
	</author>
	<link href="<?= SITE_URL ?>/opensearch" rel="search" type="application/opensearchdescription+xml" title="Standard Ebooks"/>
	<? foreach($entries as $entry){ ?>
		<?= Template::AtomFeedEntry(entry: $entry) ?>
	<? } ?>
</feed>
