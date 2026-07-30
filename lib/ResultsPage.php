<?

/**
 * Contains one page of results and its pagination metadata.
 *
 * @template T
 *
 * @property-read ?string $NextPageUrl The URL of the next page of results, or `null` if there are none.
 * @property-read ?string $PreviousPageUrl The URL of the previous page of results, or `null` if there are none.
 */
abstract class ResultsPage{
	use Traits\Accessor;

	/** @var array<T> $Results */
	public array $Results;
	public readonly int $Page;

	protected ?string $_NextPageUrl;
	protected ?string $_PreviousPageUrl;

	protected function __construct(int $page){
		$this->Page = $page;
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
}
