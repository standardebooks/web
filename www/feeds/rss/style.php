<?
$http = new HTTP2();

$contentType = [
	'application/xslt+xml',
	'application/xml',
	'text/xml'
];

$mime = $http->negotiateMimeType($contentType, 'application/xslt+xml');

header('Content-Type: ' . $mime . '; charset=utf-8');
print("<?xml version=\"1.0\" encoding=\"utf-8\"?>\n")
?>
<xsl:stylesheet version="3.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/">
	<xsl:output method="html" html-version="5.0" encoding="utf-8" indent="yes" doctype-system="about:legacy-compat"/> <? /* doctype-system outputs the HTML5 doctype */ ?>
	<xsl:template match="/">
	<?= Template::Header(isXslt: true) ?>
	<main class="opds">
		<xsl:choose>
			<xsl:when test="contains(/rss/channel/title, 'Standard Ebooks - ')">
				<h1><xsl:value-of select="substring-after(/rss/channel/title, 'Standard Ebooks - ')"/></h1>
			</xsl:when>
			<xsl:otherwise>
				<h1><xsl:value-of select="/rss/channel/title"/></h1>
			</xsl:otherwise>
		</xsl:choose>
		<p><xsl:value-of select="/rss/channel/description"/></p>
		<p>This page is an RSS 2.0 feed. The URL in your browser’s address bar (<a class="url"><xsl:attribute name="href"><xsl:value-of select="/rss/channel/atom:link/@href"/></xsl:attribute><xsl:value-of select="/rss/channel/atom:link/@href"/></a>) can be used in any RSS reader. If you’re prompted to authenticate, enter the email address you used to join the <a href="https://standardebooks.org/donate#patrons-circle">Patrons Circle</a> and a blank password.</p>
		<ol class="ebooks-list list rss">
			<xsl:for-each select="/rss/channel/item">
			<li>
				<div class="thumbnail-container">
					<a tabindex="-1">
						<xsl:attribute name="href">
							<xsl:value-of select="link"/>
						</xsl:attribute>
						<img alt="" width="224" height="335">
						<xsl:attribute name="src">
							<xsl:value-of select="media:thumbnail/@url"/>
						</xsl:attribute>
						</img>
					</a>
				</div>
				<p>
					<a>
						<xsl:attribute name="href">
							<xsl:value-of select="link"/>
						</xsl:attribute>
						<xsl:value-of select="title"/>
					</a>
				</p>
				<ul class="tags">
					<xsl:for-each select="category">
					<li>
						<p><xsl:value-of select="."/></p>
					</li>
					</xsl:for-each>
				</ul>
				<div class="details">
					<p>
						<xsl:value-of select="description"/>
					</p>
				</div>
				<xsl:if test="enclosure">
					<p class="download">Read</p>
					<ul>
						<xsl:for-each select="enclosure">
						<li>
							<p>
								<a>
									<xsl:attribute name="href">
										<xsl:value-of select="@url"/>
									</xsl:attribute>
									Download compatible epub
								</a>
							</p>
						</li>
						</xsl:for-each>
					</ul>
				</xsl:if>
			</li>
			</xsl:for-each>
		</ol>
	</main>
	<?= Template::Footer() ?>
	</xsl:template>
</xsl:stylesheet>
