<entry>
	<id><?= SITE_URL . $ebook->Url ?></id>
	<title><?= Formatter::ToPlainXmlText($ebook->Title) ?></title>
	<? foreach($ebook->Authors as $author){ ?>
		<author>
			<name><?= Formatter::ToPlainXmlText($author->Name) ?></name>
			<uri><?= SITE_URL . htmlspecialchars($ebook->AuthorsUrl, ENT_QUOTES|ENT_XML1, 'utf-8') ?></uri>
			<? if($author->FullName !== null){ ?><schema:alternateName><?= Formatter::ToPlainXmlText($author->FullName) ?></schema:alternateName><? } ?>
			<? if($author->WikipediaUrl !== null){ ?><schema:sameAs><?= Formatter::ToPlainXmlText($author->WikipediaUrl) ?></schema:sameAs><? } ?>
			<? if($author->NacoafUrl !== null){ ?><schema:sameAs><?= Formatter::ToPlainXmlText($author->NacoafUrl) ?></schema:sameAs><? } ?>
		</author>
	<? } ?>
	<dc:issued><?= $ebook->Timestamp->format('Y-m-d\TH:i:s\Z') ?></dc:issued>
	<updated><?= $ebook->ModifiedTimestamp->format('Y-m-d\TH:i:s\Z') ?></updated>
	<dc:language><?= Formatter::ToPlainXmlText($ebook->Language) ?></dc:language>
	<dc:publisher>Standard Ebooks</dc:publisher>
	<? foreach($ebook->Sources as $source){ ?>
	<dc:source><?= Formatter::ToPlainXmlText($source->Url) ?></dc:source>
	<? } ?>
	<rights>Public domain in the United States. Users located outside of the United States must check their local laws before using this ebook. Original content released to the public domain via the Creative Commons CC0 1.0 Universal Public Domain Dedication.</rights>
	<summary type="text"><?= Formatter::ToPlainXmlText($ebook->Description) ?></summary>
	<content type="text/html"><?= $ebook->LongDescription ?></content>
	<? foreach($ebook->LocTags as $subject){ ?>
	<category scheme="http://purl.org/dc/terms/LCSH" term="<?= Formatter::ToPlainXmlText($subject) ?>"/>
	<? } ?>
	<? foreach($ebook->Tags as $subject){ ?>
	<category scheme="https://standardebooks.org/vocab/subjects" term="<?= Formatter::ToPlainXmlText($subject->Name) ?>"/>
	<? } ?>
	<link href="<?= SITE_URL . $ebook->Url ?>/downloads/cover.jpg" rel="http://opds-spec.org/image" type="image/jpeg"/>
	<link href="<?= SITE_URL . $ebook->Url ?>/downloads/cover-thumbnail.jpg" rel="http://opds-spec.org/image/thumbnail" type="image/jpeg"/>
	<link href="<?= SITE_URL . $ebook->Url ?>" rel="related" title="This ebook’s page at Standard Ebooks" type="text/html"/>
	<link href="<?= SITE_URL . $ebook->EpubUrl ?>" rel="http://opds-spec.org/acquisition/open-access" title="Recommended compatible epub" type="application/epub+zip" />
	<link href="<?= SITE_URL . $ebook->AdvancedEpubUrl ?>" rel="http://opds-spec.org/acquisition/open-access" title="Advanced epub" type="application/epub+zip" />
	<link href="<?= SITE_URL . $ebook->KepubUrl ?>" rel="http://opds-spec.org/acquisition/open-access" title="Kobo Kepub epub" type="application/kepub+zip" />
	<link href="<?= SITE_URL . $ebook->Azw3Url ?>" rel="http://opds-spec.org/acquisition/open-access" title="Amazon Kindle azw3" type="application/x-mobipocket-ebook" />
	<link href="<?= SITE_URL . $ebook->TextSinglePageUrl ?>" rel="http://opds-spec.org/acquisition/open-access" title="XHTML" type="application/xhtml+xml" />
</entry>
