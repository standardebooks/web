<?
use Safe\DateTime;
use function Safe\apcu_fetch;
use function Safe\filemtime;
use function Safe\filesize;
use function Safe\glob;
use function Safe\gmdate;
use function Safe\ksort;
use function Safe\natsort;
use function Safe\preg_match;
use function Safe\preg_replace;
use function Safe\sleep;
use function Safe\sort;
use function Safe\rsort;
use function Safe\usort;


class Library{
	/**
	* @param string $query
	* @param array<string> $tags
	* @param string $sort
	* @return array<Ebook>
	*/
	public static function FilterEbooks(string $query = null, array $tags = [], string $sort = null){
		$ebooks = Library::GetEbooks();
		$matches = $ebooks;

		if($sort === null){
			$sort = SORT_NEWEST;
		}

		if(sizeof($tags) > 0 && !in_array('all', $tags)){ // 0 tags means "all ebooks"
			$matches = [];
			foreach($tags as $tag){
				foreach($ebooks as $ebook){
					if($ebook->HasTag($tag)){
						$matches[$ebook->Identifier] = $ebook;
					}
				}
			}
		}

		if($query !== null){
			$filteredMatches = [];

			foreach($matches as $ebook){
				if($ebook->Contains($query)){
					$filteredMatches[$ebook->Identifier] = $ebook;
				}
			}

			$matches = $filteredMatches;
		}

		switch($sort){
			case SORT_AUTHOR_ALPHA:
				usort($matches, function($a, $b){
					return strcmp(mb_strtolower($a->Authors[0]->SortName), mb_strtolower($b->Authors[0]->SortName));
				});
				break;

			case SORT_NEWEST:
				usort($matches, function($a, $b){
					if($a->Created < $b->Created){
						return -1;
					}
					elseif($a->Created == $b->Created){
						return 0;
					}
					else{
						return 1;
					}
				});

				$matches = array_reverse($matches);
				break;

			case SORT_READING_EASE:
				usort($matches, function($a, $b){
					if($a->ReadingEase < $b->ReadingEase){
						return -1;
					}
					elseif($a->ReadingEase == $b->ReadingEase){
						return 0;
					}
					else{
						return 1;
					}
				});

				$matches = array_reverse($matches);
				break;

			case SORT_LENGTH:
				usort($matches, function($a, $b){
					if($a->WordCount < $b->WordCount){
						return -1;
					}
					elseif($a->WordCount == $b->WordCount){
						return 0;
					}
					else{
						return 1;
					}
				});
				break;
		}

		return $matches;
	}

	/**
	 * @return array<Ebook>
	 */
	public static function GetEbooks(): array{
		// Get all ebooks, unsorted.
		return self::GetFromApcu('ebooks');
	}

	/**
	 * @return array<Ebook>
	 */
	public static function GetEbooksByAuthor(string $wwwFilesystemPath): array{
		return self::GetFromApcu('author-' . $wwwFilesystemPath);
	}

	/**
	 * @return array<Ebook>
	 */
	public static function GetEbooksByTag(string $tag): array{
		try{
			return apcu_fetch('tag-' . $tag) ?? [];
		}
		catch(Safe\Exceptions\ApcuException $ex){
			return [];
		}
	}

	/**
	 * @return array<Ebook>
	 */
	public static function GetEbooksByCollection(string $collection): array{
		// Do we have the tag's ebooks cached?
		return self::GetFromApcu('collection-' . $collection);
	}

	/**
	 * @return array<Tag>
	 */
	public static function GetTags(): array{
		return self::GetFromApcu('tags');
	}

	/**
	 * @return array<mixed>
	 */
	private static function GetFromApcu(string $variable): array{
		$results = [];

		try{
			$results = apcu_fetch($variable);
		}
		catch(Safe\Exceptions\ApcuException $ex){
			Library::RebuildCache();
			try{
				$results = apcu_fetch($variable);
			}
			catch(Safe\Exceptions\ApcuException $ex){
				// We can get here if the cache is currently rebuilding from a different process.
				// Nothing we can do but wait, so wait 20 seconds before retrying
				sleep(20);

				try{
					$results = apcu_fetch($variable);
				}
				catch(Safe\Exceptions\ApcuException $ex){
					// Cache STILL rebuilding... give up silently for now
				}
			}
		}

		return $results;
	}

	/**
	 * @return array<Ebook>
	 */
	public static function Search(string $query): array{
		$ebooks = Library::GetEbooks();
		$matches = [];

		foreach($ebooks as $ebook){
			if($ebook->Contains($query)){
				$matches[] = $ebook;
			}
		}

		return $matches;
	}

	/**
	 * @return array<Ebook>
	 */
	public static function GetEbooksFromFilesystem(?string $webRoot = WEB_ROOT): array{
		$ebooks = [];
		$contentFiles = explode("\n", trim(shell_exec('find ' . escapeshellarg($webRoot . '/ebooks/') . ' -name "content.opf" | sort') ?? ''));

		foreach($contentFiles as $path){
			if($path == '')
				continue;

			$ebookWwwFilesystemPath = '';

			try{
				$ebookWwwFilesystemPath = preg_replace('|/content\.opf|ius', '', $path);

				$ebooks[] = new Ebook($ebookWwwFilesystemPath);
			}
			catch(\Exception $ex){
				// An error in a book isn't fatal; just carry on.
			}
		}

		return $ebooks;
	}

	private static function FillBulkDownloadObject(string $file, string $downloadType): stdClass{
		$obj = new stdClass();
		$obj->Size = Formatter::ToFileSize(filesize($file));
		$obj->Updated = new DateTime('@' . filemtime($file));

		// The count of ebooks in each file is stored as a filesystem attribute
		$obj->Count = exec('attr -g se-ebook-count ' . escapeshellarg($file)) ?: null;
		if($obj->Count !== null){
			$obj->Count = intval($obj->Count);
		}

		// The subject of the batch is stored as a filesystem attribute
		$obj->Label = exec('attr -g se-label ' . escapeshellarg($file)) ?: null;
		if($obj->Label === null){
			$obj->Label = str_replace('se-ebooks-', '', basename($file, '.zip'));
		}

		$obj->UrlLabel = Formatter::MakeUrlSafe($obj->Label);

		$obj->Url = '/bulk-downloads/' . $downloadType . '/' . $obj->UrlLabel . '/' . basename($file);

		// The type of ebook in the zip is stored as a filesystem attribute
		$obj->Type = exec('attr -g se-ebook-type ' . escapeshellarg($file));
		if($obj->Type == 'epub-advanced'){
			$obj->Type = 'epub (advanced)';
		}

		$obj->UpdatedString = $obj->Updated->format('M j');
		// Add a period to the abbreviated month, but not if it's May (the only 3-letter month)
		$obj->UpdatedString = preg_replace('/^(.+?)(?<!May) /', '\1. ', $obj->UpdatedString);
		if($obj->Updated->format('Y') != gmdate('Y')){
			$obj->UpdatedString = $obj->Updated->format('M j, Y');
		}

		return $obj;
	}

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
	 * @return array<string, array<int|string, array<int|string, mixed>>>
	 */
	public static function RebuildBulkDownloadsCache(): array{
		$years = [];
		$subjects = [];

		// Generate bulk downloads by month
		$files = glob(WEB_ROOT . '/bulk-downloads/months/*/*.zip');
		rsort($files);

		foreach($files as $file){
			$obj = self::FillBulkDownloadObject($file, 'months');

			$date = new DateTime($obj->Label . '-01');
			$year = $date->format('Y');
			$month = $date->format('F');

			if(!isset($years[$year])){
				$years[$year] = [];
			}

			if(!isset($years[$year][$month])){
				$years[$year][$month] = [];
			}

			$years[$year][$month][] = $obj;
		}

		// Sort the downloads by filename extension
		foreach($years as $year => $months){
			foreach($months as $month => $items){
				$years[$year][$month] = self::SortBulkDownloads($items);
			}
		}

		apcu_store('bulk-downloads-years', $years);

		// Generate bulk downloads by subject
		$files = glob(WEB_ROOT . '/bulk-downloads/subjects/*/*.zip');
		sort($files);

		foreach($files as $file){
			$obj = self::FillBulkDownloadObject($file, 'subjects');

			if(!isset($subjects[$obj->UrlLabel])){
				$subjects[$obj->UrlLabel] = [];
			}

			$subjects[$obj->UrlLabel][] = $obj;
		}

		foreach($subjects as $subject => $items){
			$subjects[$subject] = self::SortBulkDownloads($items);
		}

		apcu_store('bulk-downloads-subjects', $subjects);


		// Generate bulk downloads by collection
		$files = glob(WEB_ROOT . '/bulk-downloads/collections/*/*.zip');
		sort($files);

		foreach($files as $file){
			$obj = self::FillBulkDownloadObject($file, 'collections');

			if(!isset($collections[$obj->UrlLabel])){
				$collections[$obj->UrlLabel] = [];
			}

			$collections[$obj->UrlLabel][] = $obj;
		}

		foreach($collections as $collection => $items){
			$collections[$collection] = self::SortBulkDownloads($items);
		}

		apcu_store('bulk-downloads-collections', $collections);

		return ['years' => $years, 'subjects' => $subjects, 'collections' => $collections];
	}

	public static function RebuildCache(): void{
		// We check a lockfile because this can be a long-running command.
		// We don't want to queue up a bunch of these in case someone is refreshing the index constantly.
		$lockVar = 'library-cache-rebuilding';
		try{
			$val = apcu_fetch($lockVar);
			return;
		}
		catch(Safe\Exceptions\ApcuException $ex){
			apcu_store($lockVar, true);
		}

		$ebooks = [];
		$collections = [];
		$ebooksByTag = [];
		$authors = [];
		$tagsByName = [];

		foreach(explode("\n", trim(shell_exec('find ' . EBOOKS_DIST_PATH . ' -name "content.opf"') ?? '')) as $filename){
			try{
				$ebookWwwFilesystemPath = preg_replace('|/content\.opf|ius', '', $filename);

				$ebook = new Ebook($ebookWwwFilesystemPath);

				$ebooks[$ebookWwwFilesystemPath] = $ebook;

				// Create the collections cache
				foreach($ebook->Collections as $collection){
					$urlSafeCollection = Formatter::MakeUrlSafe($collection->Name);
					if(!array_key_exists($urlSafeCollection, $collections)){
						$collections[$urlSafeCollection] = [];
					}

					// Some items may have the same position in a collection,
					// like _Some Do Not..._ and _No More Parades_ are both #57 in the Modern Library's 100 best novels.
					// To accomodate that, we create an anonymous object that holds the sequence number as a separate value,
					// then later we sort by that instead of by array index.
					$sortItem = new stdClass();
					$sortItem->Ebook = $ebook;
					if($collection->SequenceNumber !== null){
						$sortItem->Ordinal = $collection->SequenceNumber;
					}
					else{
						$sortItem->Ordinal = 1;
					}
					$collections[$urlSafeCollection][] = $sortItem;
				}

				// Create the tags cache
				foreach($ebook->Tags as $tag){
					$tagsByName[$tag->UrlName] = $tag;
					if(!array_key_exists($tag->UrlName, $ebooksByTag)){
						$ebooksByTag[$tag->UrlName] = [];
					}

					$ebooksByTag[$tag->UrlName][] = $ebook;
				}

				// Create the authors cache
				$authorPath = EBOOKS_DIST_PATH . rtrim(preg_replace('|^/ebooks/|ius', '', $ebook->AuthorsUrl), '/');
				if(!array_key_exists($authorPath, $authors)){
					$authors[$authorPath] = [];
				}

				$authors[$authorPath][] = $ebook;
			}
			catch(\Exception $ex){
				// An error in a book isn't fatal; just carry on.
			}
		}

		apcu_delete('ebooks');
		apcu_store('ebooks', $ebooks);

		// Before we sort the list of ebooks and lose the array keys, store them by individual ebook
		apcu_delete(new APCUIterator('/^ebook-/'));
		foreach($ebooks as $ebookWwwFilesystemPath => $ebook){
			apcu_store('ebook-' . $ebookWwwFilesystemPath, $ebook);
		}

		// Now store various collections
		apcu_delete(new APCUIterator('/^collection-/'));
		foreach($collections as $collection => $sortItems){
			// Sort the array by the ebook's ordinal in the collection. We use this custom sort function
			// because an ebook may share the same place in a collection with another ebook; see above.
			usort($sortItems, function($a, $b) {
				if ($a->Ordinal == $b->Ordinal) {
				        return 0;
				    }
				    return ($a->Ordinal < $b->Ordinal) ? -1 : 1;
			});

			// Now pull the actual ebooks out of the anonymous objects we just sorted
			$ebooks = [];
			foreach($sortItems as $sortItem){
				$ebooks[] = $sortItem->Ebook;
			}
			apcu_store('collection-' . $collection, $ebooks);
		}

		apcu_delete(new APCUIterator('/^tag-/'));
		foreach($ebooksByTag as $tagName => $ebooks){
			apcu_store('tag-' . $tagName, $ebooks);
		}

		ksort($tagsByName);
		apcu_delete('tags');
		apcu_store('tags', $tagsByName);

		apcu_delete(new APCUIterator('/^author-/'));
		foreach($authors as $author => $ebooks){
			apcu_store('author-' . $author, $ebooks);
		}

		apcu_delete($lockVar);
	}
}
