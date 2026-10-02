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

	public ?string $NextPageUrl{
		get{
			if(!$this->HasNextPage){
				return null;
			}

			$nextPage = $this->Page + 1;
			$queryParams = Http::$Request->UriQueryString->Variables;
			ksort($queryParams);
			$queryParams['page'] = $nextPage;

			return Http::$Request->RelativeUri->getPath() . '?' . http_build_query($queryParams);
		}
	}

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
}
