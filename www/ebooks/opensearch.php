<?
header('Content-Type: text/xml; charset=utf-8');
print('<?xml version="1.0" encoding="utf-8"?>');
?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/">
	<ShortName>Standard Ebooks</ShortName>
	<Description>Search the Standard Ebooks catalog.</Description>
	<Developer>Standard Ebooks</Developer>
	<Language>en-US</Language>
	<SyndicationRight>open</SyndicationRight>
	<OutputEncoding>UTF-8</OutputEncoding>
	<InputEncoding>UTF-8</InputEncoding>
	<Url type="application/xhtml+xml" template="<?= SITE_URL ?>/ebooks?query={searchTerms}&amp;per-page={count}&amp;page={startPage}"/>
	<Url type="application/rss+xml" template="<?= SITE_URL ?>/feeds/rss/all?query={searchTerms}&amp;per-page={count}&amp;page={startPage}"/>
	<Url type="application/atom+xml" template="<?= SITE_URL ?>/feeds/atom/all?query={searchTerms}&amp;per-page={count}&amp;page={startPage}"/>
	<Url type="application/atom+xml;profile=opds-catalog;kind=acquisition" template="<?= SITE_URL ?>/feeds/opds/all?query={searchTerms}&amp;per-page={count}&amp;page={startPage}"/>
	<Query role="example" searchTerms="fiction" startPage="1" count="12"/>
</OpenSearchDescription>
