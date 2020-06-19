<entry>
	<id><?= SITE_URL . $ebook->Url ?></id>
	<title><?= $ebook->Title ?></title>
	<? foreach($ebook->Authors as $author){ ?>
		<author>
			<name><?= $author->Name ?></name>
			<? if($author->WikipediaUrl !== null){ ?><uri><?= $author->WikipediaUrl ?></uri><? } ?>
			<? if($author->FullName !== null){ ?><schema:alternateName><?= $author->FullName ?></schema:alternateName><? } ?>
			<? if($author->NacoafUrl !== null){ ?><schema:sameAs><?= $author->NacoafUrl ?></schema:sameAs><? } ?>
		</author>
	<? } ?>
	<published><?= $ebook->Timestamp->format('Y-m-d\TH:i:s\Z') ?></published>
	<dc:issued><?= $ebook->Timestamp->format('Y-m-d\TH:i:s\Z') ?></dc:issued>
	<dc:language><?= $ebook->Language ?></dc:language>
	<dc:publisher>Standard Ebooks</dc:publisher>
	<? foreach($ebook->Sources as $source){ ?>
	<dc:source><?= $source->Url ?></dc:source>
	<? } ?>
	<rights>Public domain in the United States; original content released to the public domain via the Creative Commons CC0 1.0 Universal Public Domain Dedication</rights>
	<summary type="text"><?= htmlspecialchars($ebook->Description, ENT_QUOTES, 'UTF-8') ?></summary>
	<content type="text/html"><?= $ebook->LongDescription ?></content>
	<? foreach($ebook->LocTags as $subject){ ?>
	<category scheme="http://purl.org/dc/terms/LCSH" term="<?= htmlspecialchars($subject, ENT_QUOTES, 'UTF-8') ?>"/>
	<? } ?>
	<link href="<?= $ebook->Url ?>/dist/cover.jpg" rel="http://opds-spec.org/image" type="image/jpeg"/>
	<link href="<?= $ebook->Url ?>/dist/cover-thumbnail.jpg" rel="http://opds-spec.org/image/thumbnail" type="image/jpeg"/>
	<link href="<?= $ebook->EpubUrl ?>" rel="http://opds-spec.org/acquisition/open-access" type="application/epub+zip" title="Recommended compatible epub"/>
	<link href="<?= $ebook->Epub3Url ?>" rel="http://opds-spec.org/acquisition/open-access" type="application/epub+zip" title="epub"/>
	<link href="<?= $ebook->KepubUrl ?>" rel="http://opds-spec.org/acquisition/open-access" type="application/kepub+zip" title="Kobo Kepub epub"/>
	<link href="<?= $ebook->Azw3Url ?>" rel="http://opds-spec.org/acquisition/open-access" type="application/x-mobipocket-ebook" title="Amazon Kindle azw3"/>
</entry>
