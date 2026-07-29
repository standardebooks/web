<?

/**
 * Contains one page of results and its pagination metadata.
 *
 * @template T
 *
 * @property-read ?string $NextPageUrl The URL of the next page of results, or `null` if there are none.
 * @property-read ?string $PreviousPageUrl The URL of the previous page of results, or `null` if there are none.
 * @property-read array<string> $PageUrls A list all paginated URLs for the request.
 */
final class ResultsPage{
	use Traits\Accessor;

	/** @var array<T> $Results */
	public array $Results;
	public int $Page;
	public int $TotalResults;
	public int $TotalPages;

	private ?string $_NextPageUrl;
	private ?string $_PreviousPageUrl;

	/** @var array<string> $_PageUrls */
	private array $_PageUrls;

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
	}

	/**
	 * Get the URL of the previous page of results, or `null` if there are none.
	 */
	protected function GetPreviousPageUrl(): ?string{
		if(!isset($this->_PreviousPageUrl)){
			$previousPage = $this->Page - 1;

			if($previousPage < 1){
				$this->_PreviousPageUrl = null;
			}
			else{
				$queryParams = Http::$Request->UriQueryString->Variables;
				ksort($queryParams);
				$queryParams['page'] = $previousPage;

				$this->_PreviousPageUrl = Http::$Request->RelativePath . '?' . http_build_query($queryParams);
			}
		}

		return $this->_PreviousPageUrl;
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
