<?

/**
 * Contains one page of results and its pagination metadata for a set of results in which the total number of results is known.
 *
 * @template T
 *
 * @extends ResultsPage<T>
 */
class PaginatedResultsPage extends ResultsPage{
	public ?int $TotalResults = null;
	public ?int $TotalPages = null;

	public ?string $NextPageUrl{
		get{
			$nextPage = $this->Page + 1;

			if($nextPage > $this->TotalPages){
				return null;
			}

			$queryParams = Http::$Request->UriQueryString->Variables;
			ksort($queryParams);
			$queryParams['page'] = $nextPage;

			return Http::$Request->RelativeUri->getPath() . '?' . http_build_query($queryParams);
		}
	}

	/** @var array<string> $PageUrls */
	public private(set) array $PageUrls{
		get{
			if(!isset($this->PageUrls)){
				$queryParams = Http::$Request->UriQueryString->Variables;
				ksort($queryParams);

				$pageUrls = [];
				for($i = 0; $i < $this->TotalPages; $i++){
					$queryParams['page'] = $i + 1;
					$pageUrls[] = Http::$Request->RelativeUri->getPath() . '?' . http_build_query($queryParams);
				}

				$this->PageUrls = $pageUrls;
			}

			return $this->PageUrls;
		}
	}

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
}
