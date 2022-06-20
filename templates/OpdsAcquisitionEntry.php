<entry>
	<id><?= SITE_URL . $ebook->Url ?></id>
	<title><?= htmlspecialchars($ebook->Title, ENT_QUOTES|ENT_XML1, 'utf-8') ?></title>
	<? foreach($ebook->Authors as $author){ ?>
		<author>
			<name><?= htmlspecialchars($author->Name, ENT_QUOTES|ENT_XML1, 'utf-8') ?></name>
			<uri><?= SITE_URL . htmlspecialchars($ebook->AuthorsUrl, ENT_QUOTES|ENT_XML1, 'utf-8') ?></uri>
			<? if($author->FullName !== null){ ?><schema:alternateName><?= htmlspecialchars($author->FullName, ENT_QUOTES|ENT_XML1, 'utf-8') ?></schema:alternateName><? } ?>
			<? if($author->WikipediaUrl !== null){ ?><schema:sameAs><?= htmlspecialchars($author->WikipediaUrl, ENT_QUOTES|ENT_XML1, 'utf-8') ?></schema:sameAs><? } ?>
			<? if($author->NacoafUrl !== null){ ?><schema:sameAs><?= htmlspecialchars($author->NacoafUrl, ENT_QUOTES|ENT_XML1, 'utf-8') ?></schema:sameAs><? } ?>
		</author>
	<? } ?>
	<dc:issued><?= $ebook->Timestamp->format('Y-m-d\TH:i:s\Z') ?></dc:issued>
	<updated><?= $ebook->ModifiedTimestamp->format('Y-m-d\TH:i:s\Z') ?></updated>
	<dc:language><?= htmlspecialchars($ebook->Language, ENT_QUOTES|ENT_XML1, 'utf-8') ?></dc:language>
	<dc:publisher>Standard Ebooks</dc:publisher>
	<? foreach($ebook->Sources as $source){ ?>
	<dc:source><?= htmlspecialchars($source->Url, ENT_QUOTES|ENT_XML1, 'utf-8') ?></dc:source>
	<? } ?>
	<rights>Public domain in the United States. Users located outside of the United States must check their local laws before using this ebook. Original content released to the public domain via the Creative Commons CC0 1.0 Universal Public Domain Dedication.</rights>
	<summary type="text"><?= htmlspecialchars($ebook->Description, ENT_QUOTES|ENT_XML1, 'utf-8') ?></summary>
	<content type="text/html"><?= $ebook->LongDescription ?></content>
	<? foreach($ebook->LocTags as $subject){ ?>
	<category scheme="http://purl.org/dc/terms/LCSH" term="<?= htmlspecialchars($subject, ENT_QUOTES|ENT_XML1, 'utf-8') ?>"/>
	<? } ?>
	<link href="<?= $ebook->Url ?>/downloads/cover.jpg" rel="http://opds-spec.org/image" type="image/jpeg"/>
	<link href="<?= $ebook->Url ?>/downloads/cover-thumbnail.jpg" rel="http://opds-spec.org/image/thumbnail" type="image/jpeg"/>
	<link href="<?= $ebook->Url ?>" rel="related" type="text/html" title="This ebook’s page at Standard Ebooks"/>
	<link href="<?= $ebook->EpubUrl ?>" rel="http://opds-spec.org/acquisition/open-access" type="application/epub+zip" title="Recommended compatible epub"/>
	<link href="<?= $ebook->AdvancedEpubUrl ?>" rel="http://opds-spec.org/acquisition/open-access" type="application/epub+zip" title="Advanced epub"/>
	<link href="<?= $ebook->KepubUrl ?>" rel="http://opds-spec.org/acquisition/open-access" type="application/kepub+zip" title="Kobo Kepub epub"/>
	<link href="<?= $ebook->Azw3Url ?>" rel="http://opds-spec.org/acquisition/open-access" type="application/x-mobipocket-ebook" title="Amazon Kindle azw3"/>
	<link href="<?= $ebook->TextSinglePageUrl ?>" rel="http://opds-spec.org/acquisition/open-access" type="application/xhtml+xml" title="XHTML"/>
</entry>
