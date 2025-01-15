<?
$identifier = EBOOKS_IDENTIFIER_PREFIX .  trim(str_replace('.', '', HttpInput::Str(GET, 'url-path') ?? ''), '/'); // Contains the portion of the URL (without query string) that comes after `https://standardebooks.org/ebooks/`.

$ebook = null;

try{
	$ebook = Ebook::GetByIdentifier($identifier);
}
catch(Exceptions\EbookNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}

header('content-type: application/xml; charset=utf-8');
print('<?xml version="1.0" encoding="utf-8"?>');
print("\n");
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
