<?
require_once('Core.php');

// `text/xsl` is the only mime type recognized by Chrome for XSL stylesheets
header('Content-Type: text/xsl');
print("<?xml version=\"1.0\" encoding=\"utf-8\"?>\n")
?>
<xsl:stylesheet version="3.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/">
	<xsl:output method="html" html-version="5.0" encoding="utf-8" indent="yes" doctype-system="about:legacy-compat"/> <? /* doctype-system outputs the HTML5 doctype */ ?>
	<xsl:template match="/">
	<?= Template::Header(['xmlDeclaration' => false]) ?>
	<main class="opds">
		<h1><xsl:value-of select="substring-after(/atom:feed/atom:title, 'Standard Ebooks - ')"/></h1>
		<p>This page is an Atom 1.0 feed. The URL in your browser’s address bar (<a class="url"><xsl:attribute name="href"><xsl:value-of select="/atom:feed/atom:link[@rel='self']/@href"/></xsl:attribute><xsl:value-of select="/atom:feed/atom:link[@rel='self']/@href"/></a>) can be used in any Atom client.</p>
		<ol class="ebooks-list list">
			<xsl:for-each select="/atom:feed/atom:entry">
			<li>
				<div class="thumbnail-container">
					<a tabindex="-1">
						<xsl:attribute name="href">
							<xsl:value-of select="atom:link[@rel='related']/@href"/>
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
							<xsl:value-of select="atom:link[@rel='related']/@href"/>
						</xsl:attribute>
						<xsl:value-of select="atom:title"/>
					</a>
				</p>
				<div>
					<xsl:for-each select="atom:author">
						<p class="author">
							<a>
								<xsl:attribute name="href">
									<xsl:value-of select="atom:uri"/>
								</xsl:attribute>
								<xsl:value-of select="atom:name"/>
							</a>
						</p>
					</xsl:for-each>
				</div>
				<ul class="tags">
					<xsl:for-each select="atom:category[@scheme='https://standardebooks.org/vocab/subjects']">
					<li>
						<p><xsl:value-of select="@term"/></p>
					</li>
					</xsl:for-each>
				</ul>
				<div class="details">
					<p>
						<xsl:value-of select="atom:summary"/>
					</p>
				</div>
				<p class="download">Read</p>
				<ul>
					<xsl:for-each select="atom:link[@rel='enclosure']">
						<li>
							<p>
								<a>
									<xsl:attribute name="href">
										<xsl:value-of select="@href"/>
									</xsl:attribute>
									<xsl:value-of select="@title"/>
								</a>
							</p>
						</li>
					</xsl:for-each>
				</ul>
			</li>
			</xsl:for-each>
		</ol>
	</main>
	<?= Template::Footer() ?>
	</xsl:template>
</xsl:stylesheet>
