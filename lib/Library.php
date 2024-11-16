<?
use Safe\DateTimeImmutable;

use function Safe\exec;
use function Safe\filemtime;
use function Safe\filesize;
use function Safe\glob;
use function Safe\preg_replace;

class Library{
	private static function FillBulkDownloadObject(string $dir, string $downloadType, string $urlRoot): stdClass{
		$obj = new stdClass();

		// The count of ebooks in each file is stored as a filesystem attribute
		$obj->EbookCount = exec('attr -g se-ebook-count ' . escapeshellarg($dir)) ?: null;
		if($obj->EbookCount == null){
			$obj->EbookCount = 0;
		}
		else{
			$obj->EbookCount = intval($obj->EbookCount);
		}

		// The subject of the batch is stored as a filesystem attribute
		$obj->Label = exec('attr -g se-label ' . escapeshellarg($dir)) ?: null;
		if($obj->Label === null){
			$obj->Label = basename($dir);
		}

		$obj->UrlLabel = exec('attr -g se-url-label ' . escapeshellarg($dir)) ?: null;
		if($obj->UrlLabel === null){
			$obj->UrlLabel = Formatter::MakeUrlSafe($obj->Label);
		}

		$obj->Url = $urlRoot . '/' . $obj->UrlLabel;

		$obj->LabelSort = exec('attr -g se-label-sort ' . escapeshellarg($dir)) ?: null;
		if($obj->LabelSort === null){
			$obj->LabelSort = basename($dir);
		}

		$obj->ZipFiles = [];

		$files = glob($dir . '/*.zip');
		foreach($files as $file){
			$zipFile = new stdClass();
			$zipFile->Size = Formatter::ToFileSize(filesize($file));

			$zipFile->Url = '/bulk-downloads/' . $downloadType . '/' . $obj->UrlLabel . '/' . basename($file);

			// The type of ebook in the zip is stored as a filesystem attribute
			$zipFile->Type = exec('attr -g se-ebook-type ' . escapeshellarg($file));
			if($zipFile->Type == 'epub-advanced'){
				$zipFile->Type = 'epub (advanced)';
			}

			$obj->ZipFiles[] = $zipFile;
		}

		/** @throws void */
		$obj->Updated = new DateTimeImmutable('@' . filemtime($files[0]));
		$obj->UpdatedString = $obj->Updated->format('M j');
		// Add a period to the abbreviated month, but not if it's May (the only 3-letter month)
		$obj->UpdatedString = preg_replace('/^(.+?)(?<!May) /', '\1. ', $obj->UpdatedString);
		if($obj->Updated->format('Y') != NOW->format('Y')){
			$obj->UpdatedString = $obj->Updated->format('M j, Y');
		}

		// Sort the downloads by filename extension
		$obj->ZipFiles = self::SortBulkDownloads($obj->ZipFiles);

		return $obj;
	}

	/**
	 * @param array<int, stdClass> $items
	 *
	 * @return array<int, stdClass>
	 */
	private static function SortBulkDownloads(array $items): array{
		// This sorts our items in a special order, epub first and advanced epub last
		$result = [];

		foreach($items as $key => $item){
			if($item->Type == 'epub'){
				$result[0] = $item;
			}
			if($item->Type == 'azw3'){
				$result[1] = $item;
			}
			if($item->Type == 'kepub'){
				$result[2] = $item;
			}
			if($item->Type == 'xhtml'){
				$result[3] = $item;
			}
			if($item->Type == 'epub (advanced)'){
				$result[4] = $item;
			}
		}

		ksort($result);

		return $result;
	}

	/**
	 * @return array{'months': array<string, array<string, stdClass>>, 'subjects': array<stdClass>, 'collections': array<stdClass>, 'authors': array<stdClass>}
	 *
	 * @throws Exceptions\AppException
	 */
	public static function RebuildBulkDownloadsCache(): array{
		$collator = Collator::create('en_US'); // Used for sorting letters with diacritics like in author names
		if($collator === null){
			throw new Exceptions\AppException('Couldn\'t create collator object when rebuilding bulk download cache.');
		}
		/** @var array<string, array<string, stdClass>> $months */
		$months = [];
		$subjects = [];
		$collections = [];
		$authors = [];

		// Generate bulk downloads by month
		// These get special treatment because they're sorted by two dimensions,
		// year and month.
		$dirs = glob(WEB_ROOT . '/bulk-downloads/months/*/', GLOB_NOSORT);
		rsort($dirs);

		foreach($dirs as $dir){
			$obj = self::FillBulkDownloadObject($dir, 'months', '/months');

			try{
				$date = new DateTimeImmutable($obj->Label . '-01');
			}
			catch(\Exception){
				throw new Exceptions\AppException('Couldn\'t parse date on bulk download object.');
			}

			/** @var string $year Required to satisfy PHPStan */
			$year = $date->format('Y');
			$month = $date->format('F');

			if(!isset($months[$year])){
				$months[$year] = [];
			}

			$months[$year][$month] = $obj;
		}

		apcu_store('bulk-downloads-months', $months, 43200); // 12 hours

		// Generate bulk downloads by subject
		foreach(glob(WEB_ROOT . '/bulk-downloads/subjects/*/', GLOB_NOSORT) as $dir){
			$subjects[] = self::FillBulkDownloadObject($dir, 'subjects', '/subjects');
		}
		usort($subjects, function($a, $b){ return $a->LabelSort <=> $b->LabelSort; });

		apcu_store('bulk-downloads-subjects', $subjects, 43200); // 12 hours

		// Generate bulk downloads by collection
		foreach(glob(WEB_ROOT . '/bulk-downloads/collections/*/', GLOB_NOSORT) as $dir){
			$collections[] = self::FillBulkDownloadObject($dir, 'collections', '/collections');
		}
		usort($collections, function(stdClass $a, stdClass $b) use($collator): int{
			$result = $collator->compare($a->LabelSort, $b->LabelSort);
			return $result === false ? 0 : $result;
		});

		apcu_store('bulk-downloads-collections', $collections, 43200); // 12 hours

		// Generate bulk downloads by authors
		foreach(glob(WEB_ROOT . '/bulk-downloads/authors/*/', GLOB_NOSORT) as $dir){
			$authors[] = self::FillBulkDownloadObject($dir, 'authors', '/ebooks');
		}
		usort($authors, function(stdClass $a, stdClass $b) use($collator): int{
			$result = $collator->compare($a->LabelSort, $b->LabelSort);
			return $result === false ? 0 : $result;
		});

		apcu_store('bulk-downloads-authors', $authors, 43200); // 12 hours

		return ['months' => $months, 'subjects' => $subjects, 'collections' => $collections, 'authors' => $authors];
	}
}
