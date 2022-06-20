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
	<main class="opds">
		<h1><xsl:value-of select="/atom:feed/atom:title"/></h1>
		<p>This page is an OPDS feed. The URL in your browser’s address bar (<a class="url"><xsl:attribute name="href"><xsl:value-of select="/atom:feed/atom:link[@rel='self']/@href"/></xsl:attribute><xsl:value-of select="/atom:feed/atom:link[@rel='self']/@href"/></a>) can be used in any OPDS client.</p>
		<xsl:if test="/atom:feed/atom:entry[./atom:link[starts-with(@type, 'application/atom+xml;profile=opds-catalog;kind=')]]">
			<ol class="rss">
				<xsl:for-each select="/atom:feed/atom:entry[./atom:link[starts-with(@type, 'application/atom+xml;profile=opds-catalog;kind=')]]">
				<li>
					<a>
						<xsl:attribute name="href">
							<xsl:value-of select="atom:link/@href"/>
						</xsl:attribute>
						<xsl:value-of select="atom:title"/>
					</a>
					<p>
						<xsl:value-of select="atom:content"/>
					</p>
				</li>
				</xsl:for-each>
			</ol>
		</xsl:if>
		<xsl:if test="/atom:feed/atom:entry[./atom:link[@rel='http://opds-spec.org/acquisition/open-access']]">
			<ol class="ebooks-list list">
				<xsl:for-each select="/atom:feed/atom:entry[./atom:link[@rel='http://opds-spec.org/acquisition/open-access']]">
				<li>
					<div class="thumbnail-container">
						<a tabindex="-1">
							<xsl:attribute name="href">
								<xsl:value-of select="atom:link[@rel='related']/@href"/>
							</xsl:attribute>
							<img alt="The cover for the Standard Ebooks edition of Winnie-the-Pooh, by A. A. Milne" width="224" height="335">
							<xsl:attribute name="src">
								<xsl:value-of select="atom:link[@rel='http://opds-spec.org/image/thumbnail']/@href"/>
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
						<xsl:for-each select="atom:link[@rel='http://opds-spec.org/acquisition/open-access']">
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
		</xsl:if>
	</main>
	<?= Template::Footer() ?>
	</xsl:template>
</xsl:stylesheet>
