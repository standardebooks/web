<?
require_once('Core.php');

// `text/xsl` is the only mime type recognized by Chrome for XSL stylesheets
header('Content-Type: text/xsl');
print("<?xml version=\"1.0\" encoding=\"utf-8\"?>\n")
?>
<xsl:stylesheet version="3.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:atom="http://www.w3.org/2005/Atom">
	<xsl:output method="html" html-version="5.0" encoding="utf-8" indent="yes" doctype-system="about:legacy-compat"/> <? /* doctype-system outputs the HTML5 doctype */ ?>
	<xsl:template match="/">
	<?= Template::Header(['xmlDeclaration' => false]) ?>
	<main>
		<h1><xsl:value-of select="substring-after(/rss/channel/title, 'Standard Ebooks - ')"/></h1>
		<p><xsl:value-of select="/rss/channel/description"/></p>
		<p>This page is an RSS feed. The URL in your browser’s address bar (<a class="url"><xsl:attribute name="href"><xsl:value-of select="/rss/channel/atom:link/@href"/></xsl:attribute><xsl:value-of select="/rss/channel/atom:link/@href"/></a>) can be used in any RSS reader.</p>
		<ol class="rss">
			<xsl:for-each select="/rss/channel/item">
			<li>
				<a>
					<xsl:attribute name="href">
						<xsl:value-of select="link"/>
					</xsl:attribute>
					<xsl:value-of select="title"/>
				</a>
				<ul class="tags">
					<xsl:for-each select="category">
					<li>
						<p><xsl:value-of select="."/></p>
					</li>
					</xsl:for-each>
				</ul>
				<p>
					<xsl:value-of select="description"/>
				</p>
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
