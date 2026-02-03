<?

use Safe\DateTimeImmutable;

use function Safe\preg_replace;

/**
 * @property string $LabelUrl
 * @property string $UpdatedString
 * @property array<BulkDownloadZipFile> $ZipFiles
 */
class BulkDownloadCollection{
	use Traits\Accessor;

	public Enums\BulkDownloadLabelType $LabelType;
	public string $LabelName;
	public string $LabelSort;
	public ?string $LabelUrlSegment;
	public int $EbookCount = 0;
	public DateTimeImmutable $Updated;

	protected ?string $_LabelUrl;
	protected ?string $_UpdatedString;
	/** @var array<BulkDownloadZipFile> $_ZipFiles */
	protected array $_ZipFiles;

	/** @var array<Ebook> $Ebooks */
	public array $Ebooks;

	protected function GetLabelUrl(): string{
		if(isset($this->_LabelUrl)){
			return $this->_LabelUrl;
		}

		switch($this->LabelType){
			case Enums\BulkDownloadLabelType::Subject:
				$this->_LabelUrl = '/subjects/' . $this->LabelUrlSegment;
				break;
			case Enums\BulkDownloadLabelType::Collection:
				$this->_LabelUrl = '/collections/' . $this->LabelUrlSegment;
				break;
			case Enums\BulkDownloadLabelType::Author:
				$this->_LabelUrl = '/ebooks/' . $this->LabelUrlSegment;
				break;
			case Enums\BulkDownloadLabelType::Month:
				$this->_LabelUrl = '/months/' . $this->LabelUrlSegment;
				break;
		}

		return $this->_LabelUrl;
	}

	protected function GetUpdatedString(): string{
		if(isset($this->_UpdatedString)){
			return $this->_UpdatedString;
		}

		$this->_UpdatedString = $this->Updated->format('M j');
		// Add a period to the abbreviated month, but not if it's May (the only 3-letter month).
		$this->_UpdatedString = preg_replace('/^(.+?)(?<!May) /', '\1. ', $this->_UpdatedString);
		if($this->Updated->format('Y') != NOW->format('Y')){
			$this->_UpdatedString = $this->Updated->format(Enums\DateTimeFormat::ShortDate->value);
		}

		return $this->_UpdatedString;
	}

	/**
	 * @return array<BulkDownloadZipFile>
	 */
	protected function GetZipFiles(): array{
		return $this->_ZipFiles ??= Db::Query('
							SELECT *
							from BulkDownloadZipFiles
							where LabelType = ?
								and LabelName = ?
							order by Format
					', [$this->LabelType, $this->LabelName], BulkDownloadZipFile::class);
	}

	public function AddZipFile(Enums\BulkDownloadFormatType $format, string $downloadUrl, int $downloadByteCount): void{
		$zipFile = new BulkDownloadZipFile();
		$zipFile->LabelType = $this->LabelType;
		$zipFile->LabelName = $this->LabelName;
		$zipFile->Format = $format;
		$zipFile->DownloadUrl = $downloadUrl;
		$zipFile->DownloadByteCount = $downloadByteCount;

		$this->_ZipFiles[] = $zipFile;
	}

	public function AddEbook(Ebook $ebook): void{
		// Index by `Ebook::$EbookId` to prevent adding the same ebook twice.
		if(!isset($this->Ebooks[$ebook->EbookId])){
			$this->Ebooks[$ebook->EbookId] = $ebook;
			$this->EbookCount++;
			if(isset($ebook->EbookUpdated)){
				if(!isset($this->Updated) || $ebook->EbookUpdated > $this->Updated){
					$this->Updated = $ebook->EbookUpdated;
				}
			}
		}
	}

	/**
	 * @throws Exceptions\BulkDownloadCollectionNotFoundException
	 */
	public static function GetByCollectionUrl(string $collectionUrl): BulkDownloadCollection{
		return BulkDownloadCollection::GetByLabelTypeAndUrl(Enums\BulkDownloadLabelType::Collection, $collectionUrl);
	}

	/**
	 * @throws Exceptions\BulkDownloadCollectionNotFoundException
	 */
	public static function GetByAuthorUrl(string $authorUrl): BulkDownloadCollection{
		return BulkDownloadCollection::GetByLabelTypeAndUrl(Enums\BulkDownloadLabelType::Author, $authorUrl);
	}

	/**
	 * @throws Exceptions\BulkDownloadCollectionNotFoundException
	 */
	private static function GetByLabelTypeAndUrl(Enums\BulkDownloadLabelType $labelType, ?string $labelUrl): BulkDownloadCollection{
		if($labelUrl === null){
			throw new Exceptions\BulkDownloadCollectionNotFoundException('Invalid collection URL: ' . $labelUrl);
		}

		return Db::Query('
				SELECT *
				from BulkDownloadCollections
				where LabelType = ?
					and LabelUrlSegment = ?
			', [$labelType, $labelUrl],
			BulkDownloadCollection::class)[0] ?? throw new Exceptions\BulkDownloadCollectionNotFoundException('Invalid collection URL: ' . $labelUrl);
	}

	/**
	 * Sort by `BulkDownloadCollection`s by two dimensions, year and month.
	 *
	 * @return array<string, array<string, BulkDownloadCollection>>
	 */
	public static function GetAllByMonthLabelType(): array{
		$bulkDownloadCollections = self::GetAllByLabelType(Enums\BulkDownloadLabelType::Month);

		$months = [];
		foreach(array_reverse($bulkDownloadCollections) as $bdc){
			$date = DateTimeImmutable::createFromFormat('Y-m-d', $bdc->LabelName . '-01');
			$year = $date->format('Y');
			$month = $date->format('F');

			if(!isset($months[$year])){
				$months[$year] = [];
			}

			$months[$year][$month] = $bdc;
		}

		/** @var array<string, array<string, BulkDownloadCollection>> $months */
		return $months;
	}

	/**
	 * @return array<BulkDownloadCollection>
	 */
	public static function GetAllByLabelType(Enums\BulkDownloadLabelType $labelType): array{
		return Db::Query('
				SELECT *
				from BulkDownloadCollections
				where LabelType = ?
				order by LabelSort
			', [$labelType], BulkDownloadCollection::class);

	}

	/**
	 * @throws Exceptions\InvalidBulkDownloadCollectionException
	 */
	public function Validate(): void{
		$error = new Exceptions\InvalidBulkDownloadCollectionException();

		$this->LabelName = trim($this->LabelName ?? '');
		if($this->LabelName == ''){
			$error->Add(new Exceptions\BulkDownloadCollectionLabelNameRequiredException());
		}

		$this->LabelSort = trim($this->LabelSort ?? '');
		if($this->LabelSort == ''){
			$error->Add(new Exceptions\BulkDownloadCollectionLabelSortRequiredException());
		}

		if($this->EbookCount <= 0){
			$error->Add(new Exceptions\InvalidBulkDownloadCollectionEbookCountException('Invalid BulkDownloadCollection EbookCount: ' . $this->EbookCount));
		}

		if(!isset($this->Updated) || $this->Updated > NOW){
			$error->Add(new Exceptions\InvalidBulkDownloadCollectionUpdatedDatetimeException($this->Updated));
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	/**
	 * @throws Exceptions\InvalidBulkDownloadCollectionException
	 * @throws Exceptions\InvalidBulkDownloadZipFileException
	 */
	public function Create(): void{
		$this->Validate();

		Db::Query('
			INSERT into BulkDownloadCollections (LabelType, LabelName, LabelSort, LabelUrlSegment, EbookCount, Updated)
			values (?,
				?,
				?,
				?,
				?,
				?)
			on duplicate key update
				LabelSort = value(LabelSort),
				LabelUrlSegment = value(LabelUrlSegment),
				EbookCount = value(EbookCount),
				Updated = value(Updated)
		', [$this->LabelType, $this->LabelName, $this->LabelSort, $this->LabelUrlSegment, $this->EbookCount, $this->Updated]);

		foreach($this->ZipFiles as $zipFile){
			$zipFile->Create();
		}
	}
}
