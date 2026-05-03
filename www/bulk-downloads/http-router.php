<?
/**
 * GET		/ebooks/:author-url-name/downloads
 * GET		/collections/:collection-url-name/downloads
 */

try{
	$bulkDownloadCollection = null;
	$collectionUrlName = HttpInput::Str(GET, 'collection-url-name');
	$authorUrlName = HttpInput::Str(GET, 'author-url-name');

	if($collectionUrlName !== null){
		$bulkDownloadCollection = BulkDownloadCollection::GetByCollectionUrl($collectionUrlName);
	}

	if($authorUrlName !== null){
		$bulkDownloadCollection = BulkDownloadCollection::GetByAuthorUrl($authorUrlName);
	}

	if($bulkDownloadCollection === null){
		throw new Exceptions\BulkDownloadCollectionNotFoundException();
	}

	HttpInput::RouteRequest(resource: $bulkDownloadCollection);
}
catch(Exceptions\NotFoundException){
	Template::ExitWithCode(Enums\HttpCode::NotFound);
}
