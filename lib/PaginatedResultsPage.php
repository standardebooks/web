<?

/**
 * Contains one page of results and its pagination metadata for a set of results in which the total number of results is known.
 *
 * @template T
 *
 * @extends ResultsPage<T>
 *
 * @property-read array<string> $PageUrls A list all paginated URLs for the request.
 */
class PaginatedResultsPage extends ResultsPage{
	public ?int $TotalResults = null;
	public ?int $TotalPages = null;

	/** @var array<string> $_PageUrls */
	private array $_PageUrls;

	/**
	 * Create a paginated result object.
	 *
	 * @param array<T> $results
	 */
	public function __construct(array $results, int $page, int $totalResults, int $itemsPerPage){
		$this->Results = $results;
		$this->TotalResults = $totalResults;
		$this->TotalPages = $itemsPerPage > 0 ? (int)ceil($totalResults / $itemsPerPage) : ($totalResults > 0 ? 1 : 0);

		parent::__construct($page);
	}

	/**
	 * Get the URL of the next page of results, or `null` if there are none.
	 */
	protected function GetNextPageUrl(): ?string{
		if(!isset($this->_NextPageUrl)){
			$nextPage = $this->Page + 1;

			if($nextPage > $this->TotalPages){
				$this->_NextPageUrl = null;
			}
			else{
				$queryParams = Http::$Request->UriQueryString->Variables;
				ksort($queryParams);
				$queryParams['page'] = $nextPage;

				$this->_NextPageUrl = Http::$Request->RelativePath . '?' . http_build_query($queryParams);
			}
		}

		return $this->_NextPageUrl;
	}

	/**
	 * Get the URLs of all pages of results.
	 *
	 * @return array<string>
	 */
	protected function GetPageUrls(): array{
		if(!isset($this->_PageUrls)){
			$queryParams = Http::$Request->UriQueryString->Variables;

			ksort($queryParams);

			$pageUrls = [];
			for($i = 0; $i < $this->TotalPages; $i++){
				$queryParams['page'] = $i + 1;
				$pageUrls[] = Http::$Request->RelativePath . '?' . http_build_query($queryParams);
			}

			$this->_PageUrls = $pageUrls;
		}

		return $this->_PageUrls;
	}
}
