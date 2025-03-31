<?
header('Content-Type: application/opensearchdescription+xml'); // Can't include `charset` because Chrome doesn't like it.
print("<?xml version=\"1.0\" encoding=\"utf-8\"?>\n");
?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/">
	<ShortName>Standard Ebooks</ShortName>
	<Description>Search the Standard Ebooks catalog.</Description>
	<Developer>Standard Ebooks</Developer>
	<Language>en-US</Language>
	<SyndicationRight>open</SyndicationRight>
	<OutputEncoding>UTF-8</OutputEncoding>
	<InputEncoding>UTF-8</InputEncoding>
	<Image width="16" height="16" type="image/x-icon"><?= SITE_URL ?>/favicon-16x16.ico</Image>
	<Image width="48" height="48" type="image/x-icon"><?= SITE_URL ?>/favicon.ico</Image>
	<Image width="64" height="64" type="image/png"><?= SITE_URL ?>/favicon-64x64.png</Image>
	<Url type="application/opensearchdescription+xml" rel="self" template="<?= SITE_URL ?>/opensearch" />
	<Url type="text/html" template="<?= SITE_URL ?>/ebooks?query={searchTerms}&amp;per-page={count}&amp;page={startPage}"/><? // For compatibility; most OpenSearch parsers don't understand `application/xhtml+xml`. ?>

	<Url type="application/xhtml+xml" template="<?= SITE_URL ?>/ebooks?query={searchTerms}&amp;per-page={count}&amp;page={startPage}"/>
	<Url type="application/rss+xml" template="<?= SITE_URL ?>/feeds/rss/all?query={searchTerms}&amp;per-page={count}&amp;page={startPage}"/>
	<Url type="application/atom+xml" template="<?= SITE_URL ?>/feeds/atom/all?query={searchTerms}&amp;per-page={count}&amp;page={startPage}"/>
	<Url type="application/atom+xml;profile=opds-catalog;kind=acquisition" template="<?= SITE_URL ?>/feeds/opds/all?query={searchTerms}&amp;per-page={count}&amp;page={startPage}"/>
	<Query role="example" searchTerms="fiction" startPage="1" count="12"/>
</OpenSearchDescription>
