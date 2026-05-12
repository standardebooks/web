<?
/**
 * GET		/collections/:collection-url-name/feeds
 * GET		/ebooks/:author-url-name/feeds
 */

try{
	$authorUrlName = Http::$Request->QueryString->Get('author-url-name');
	$collectionUrlName = Http::$Request->QueryString->Get('collection-url-name');
	$collectionType = null;
	$target = null;

	if($authorUrlName !== null){
		$collectionType = Enums\FeedCollectionType::Authors;
		$target = $authorUrlName;
	}

	if($collectionUrlName !== null){
		$collectionType = Enums\FeedCollectionType::Collections;
		$target = $collectionUrlName;
	}

	if($target === null || $collectionType === null){
		throw new Exceptions\CollectionNotFoundException();
	}

	$filePath = WEB_ROOT . '/feeds/opds/' . $collectionType->value . '/' . $target . '.xml';
	if(!is_file($filePath)){
		throw new Exceptions\CollectionNotFoundException();
	}

	Http::$Request->Route(resource: ['filePath' => $filePath, 'collectionType' => $collectionType]);
}
catch(Exceptions\CollectionNotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
