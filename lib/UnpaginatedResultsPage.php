<?

/**
 * Contains one page of results and its pagination metadata for a set of results in which the total number of results is unknown.
 *
 * @template T
 *
 * @extends ResultsPage<T>
 */
class UnpaginatedResultsPage extends ResultsPage{
	public bool $HasNextPage;

	/**
	 * Create a paginated result object.
	 *
	 * @param array<T> $results
	 */
	public function __construct(array $results, int $page, bool $hasNextPage){
		$this->Results = $results;
		$this->HasNextPage = $hasNextPage;

		parent::__construct($page);
	}

	/**
	 * Get the URL of the next page of results, or `null` if there are none.
	 */
	protected function GetNextPageUrl(): ?string{
		if(!isset($this->_NextPageUrl)){
			if($this->HasNextPage){
				$nextPage = $this->Page + 1;
				$queryParams = Http::$Request->UriQueryString->Variables;
				ksort($queryParams);
				$queryParams['page'] = $nextPage;

				$this->_NextPageUrl = Http::$Request->RelativePath . '?' . http_build_query($queryParams);
			}
			else{
				$this->_NextPageUrl = null;
			}
		}

		return $this->_NextPageUrl;
	}
}
