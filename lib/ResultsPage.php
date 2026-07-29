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
}
