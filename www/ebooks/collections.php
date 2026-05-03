<?
/**
 * GET	/ebooks/:url-path/collections
 *
 * Get an XML representation of the `Collection`s this `Ebook` belongs to. Used for admin purposes, usually for `EbookPlaceholders`.
 */

/** @var non-falsy-string $urlPath Contains the portion of the URL (without query string) that comes after `https://standardebooks.org/ebooks/`. */
$urlPath = EBOOKS_IDENTIFIER_PREFIX . trim(str_replace('.', '', HttpInput::Str(GET, 'url-path') ?? ''), '/');

try{
	$ebook = Ebook::GetByIdentifier($urlPath);
}
catch(Exceptions\EbookNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}

header('content-type: application/xml; charset=utf-8');
print("<?xml version=\"1.0\" encoding=\"utf-8\"?>\n");
?>
<collections>
	<? foreach($ebook->CollectionMemberships as $collectionMembership){ ?>
		<collection>
			<meta id="collection-<?= $collectionMembership->SortOrder + 1 ?>" property="belongs-to-collection"><?= Formatter::EscapeXml($collectionMembership->Collection->Name) ?></meta>
			<? if($collectionMembership->Collection->Type !== null){ ?>
				<meta property="collection-type" refines="#collection-<?= $collectionMembership->SortOrder + 1 ?>"><?= $collectionMembership->Collection->Type->value ?></meta>
			<? } ?>
			<? if($collectionMembership->SequenceNumber !== null){ ?>
				<meta property="group-position" refines="#collection-<?= $collectionMembership->SortOrder + 1 ?>"><?= $collectionMembership->SequenceNumber ?></meta>
			<? } ?>
		</collection>
	<? } ?>
</collections>
