<?
/**
 * GET		/ebooks/:author-url-name/downloads
 * GET		/collections/:collection-url-name/downloads
 */

try{
	$bulkDownloadCollection = null;
	$collectionUrlName = Http::$Request->QueryString->Get('collection-url-name');
	$authorUrlName = Http::$Request->QueryString->Get('author-url-name');

	if($collectionUrlName !== null){
		$bulkDownloadCollection = BulkDownloadCollection::GetByCollectionUrl($collectionUrlName);
	}

	if($authorUrlName !== null){
		$bulkDownloadCollection = BulkDownloadCollection::GetByAuthorUrl($authorUrlName);
	}

	if($bulkDownloadCollection === null){
		throw new Exceptions\BulkDownloadCollectionNotFoundException();
	}

	Http::$Request->Route(resource: $bulkDownloadCollection);
}
catch(Exceptions\NotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
