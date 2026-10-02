<?

/**
 * Contains one page of results and its pagination metadata.
 *
 * @template T
 */
abstract class ResultsPage{
	/** @var array<T> $Results */
	public array $Results;
	public readonly int $Page;

	abstract public ?string $NextPageUrl{
		get;
	}

	public ?string $PreviousPageUrl{
		get{
			$previousPage = $this->Page - 1;

			if($previousPage < 1){
				return null;
			}

			$queryParams = Http::$Request->UriQueryString->Variables;
			ksort($queryParams);
			$queryParams['page'] = $previousPage;

			return Http::$Request->RelativeUri->getPath() . '?' . http_build_query($queryParams);
		}
	}

	protected function __construct(int $page){
		$this->Page = $page;
	}
}
