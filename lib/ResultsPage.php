<?

/**
 * Contains one page of results and its pagination metadata.
 *
 * @template T
 */
final class ResultsPage{
	/** @var array<T> $Results */
	public array $Results;
	public int $Page;
	public int $TotalResults;
	public int $TotalPages;

	/** The URL of the next page of results, or `null` if there are none. */
	public readonly ?string $NextPageUrl;

	/** The URL of the previous page of results, or `null` if there are none. */
	public readonly ?string $PreviousPageUrl;

	/** @var array<string> $PageUrls */
	public readonly array $PageUrls;

	/**
	 * Create a paginated result object.
	 *
	 * @param array<T> $results
	 */
	public function __construct(array $results, int $page, int $totalResults, int $itemsPerPage){
		$this->Results = $results;
		$this->Page = $page;
		$this->TotalResults = $totalResults;
		$this->TotalPages = $itemsPerPage > 0 ? (int)ceil($totalResults / $itemsPerPage) : ($totalResults > 0 ? 1 : 0);

		$queryParams = Http::$Request->QueryString->Variables;

		ksort($queryParams);

		// Calculate the previous page URL.
		$previousPage = $this->Page - 1;

		if($previousPage < 1){
			$this->PreviousPageUrl = null;
		}
		else{
			$queryParams['page'] = $previousPage;
			$this->PreviousPageUrl = Http::$Request->RelativePath . '?' . http_build_query($queryParams);
		}

		// Calculate the next page URL.
		$nextPage = $this->Page + 1;

		if($nextPage > $this->TotalPages){
			$this->NextPageUrl = null;
		}
		else{
			$queryParams['page'] = $nextPage;
			$this->NextPageUrl = Http::$Request->RelativePath . '?' . http_build_query($queryParams);
		}

		$pageUrls = [];
		for($i = 0; $i < $this->TotalPages; $i++){
			$queryParams['page'] = $i + 1;
			$pageUrls[] = Http::$Request->RelativePath . '?' . http_build_query($queryParams);
		}

		$this->PageUrls = $pageUrls;
	}
}
