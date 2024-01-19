<?
use Safe\DateTime;
use function Safe\apcu_fetch;
use function Safe\exec;
use function Safe\filemtime;
use function Safe\filesize;
use function Safe\glob;
use function Safe\ksort;
use function Safe\preg_replace;
use function Safe\shell_exec;
use function Safe\sleep;
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
		catch(Safe\Exceptions\ApcuException){
			return [];
		}
	}

	/**
	 * @return array<string, Collection>
	 */
	public static function GetEbookCollections(): array{
		return self::GetFromApcu('collections');
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
	* @param string $query
	* @param string $status
	* @param string $sort
	* @return array<Artwork>
	*/
	public static function FilterArtwork(string $query = null, string $status = null, string $sort = null, int $submitterUserId = null): array{
		// $status is either the string value of an ArtworkStatus enum, or one of these special statuses:
		// null: same as "all"
		// "all": Show all approved and in use artwork
		// "all-admin": Show all artwork regardless of status
		// "all-submitter": Show all approved and in use artwork, plus unverified artwork from the submitter
		// "unverified-submitter": Show unverified artwork from the submitter
		// "in-use": Show only in-use artwork

		$statusCondition = '';
		$params = [];

		if($status === null || $status == 'all'){
			$statusCondition = 'Status = ?';
			$params[] = ArtworkStatus::Approved->value;
		}
		elseif($status == 'all-admin'){
			$statusCondition = 'true';
		}
		elseif($status == 'in-use'){
			$statusCondition = 'EbookWwwFilesystemPath is not null';
		}
		elseif($status == 'all-submitter' && $submitterUserId !== null){
			$statusCondition = 'Status = ? or (Status = ? and SubmitterUserId = ?)';
			$params[] = ArtworkStatus::Approved->value;
			$params[] = ArtworkStatus::Unverified->value;
			$params[] = $submitterUserId;
		}
		elseif($status == 'unverified-submitter' && $submitterUserId !== null){
			$statusCondition = 'Status = ? and SubmitterUserId = ?';
			$params[] = ArtworkStatus::Unverified->value;
			$params[] = $submitterUserId;
		}
		elseif($status == ArtworkStatus::Approved->value){
			$statusCondition = 'Status = ? and EbookWwwFilesystemPath is null';
			$params[] = ArtworkStatus::Approved->value;
		}
		else{
			$statusCondition = 'Status = ?';
			$params[] = $status;
		}

		$orderBy = 'Created desc';
		if($sort == SORT_COVER_ARTIST_ALPHA){
			$orderBy = 'Name';
		}
		elseif($sort == SORT_COVER_ARTWORK_COMPLETED_NEWEST){
			$orderBy = 'CompletedYear desc';
		}

		$artworks = [];
			$artworks = Db::Query('
				SELECT *
				from Artworks
				where ' . $statusCondition .
				' order by ' . $orderBy, $params, 'Artwork');

		$matches = $artworks;

		if($query !== null){
			$filteredMatches = [];

			foreach($matches as $artwork){
				if($artwork->Contains($query)){
					$filteredMatches[] = $artwork;
				}
			}

			$matches = $filteredMatches;
		}

		return $matches;
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
			try{
				// If we can't fetch this variable, rebuild the whole cache.
				apcu_fetch('is-cache-fresh');
			}
			catch(Safe\Exceptions\ApcuException $ex){
				Library::RebuildCache();
				try{
					$results = apcu_fetch($variable);
				}
				catch(Safe\Exceptions\ApcuException){
					// We can get here if the cache is currently rebuilding from a different process.
					// Nothing we can do but wait, so wait 20 seconds before retrying
					sleep(20);

					try{
						$results = apcu_fetch($variable);
					}
					catch(Safe\Exceptions\ApcuException){
						// Cache STILL rebuilding... give up silently for now
					}
				}
			}
		}

		if(!is_array($results)){
			$results = [$results];
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

		$contentFiles = explode("\n", trim(shell_exec('find ' . escapeshellarg($webRoot . '/ebooks/') . ' -name "content.opf" | sort')));

		foreach($contentFiles as $path){
			if($path == '')
				continue;

			$ebookWwwFilesystemPath = '';

			try{
				$ebookWwwFilesystemPath = preg_replace('|/content\.opf|ius', '', $path);

				$ebooks[] = new Ebook($ebookWwwFilesystemPath);
			}
			catch(\Exception){
				// An error in a book isn't fatal; just carry on.
			}
		}

		return $ebooks;
	}

	private static function FillBulkDownloadObject(string $dir, string $downloadType, string $urlRoot): stdClass{
		$obj = new stdClass();
		$now = new DateTime('now', new DateTimeZone('UTC'));

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

		$obj->Updated = new DateTime('@' . filemtime($files[0]));
		$obj->UpdatedString = $obj->Updated->format('M j');
		// Add a period to the abbreviated month, but not if it's May (the only 3-letter month)
		$obj->UpdatedString = preg_replace('/^(.+?)(?<!May) /', '\1. ', $obj->UpdatedString);
		if($obj->Updated->format('Y') != $now->format('Y')){
			$obj->UpdatedString = $obj->Updated->format('M j, Y');
		}

		// Sort the downloads by filename extension
		$obj->ZipFiles = self::SortBulkDownloads($obj->ZipFiles);

		return $obj;
	}

	/**
	 * @param array<int, stdClass> $items
	 * @return array<string, array<int|string, array<int|string, mixed>>>
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
	 * @return array<string, array<int|string, array<int|string, mixed>>>
	 */
	public static function RebuildBulkDownloadsCache(): array{
		$collator = Collator::create('en_US'); // Used for sorting letters with diacritics like in author names
		if($collator === null){
			throw new Exceptions\AppException('Couldn\'t create collator object when rebuilding bulk download cache.');
		}
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

			$date = new DateTime($obj->Label . '-01');
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
		usort($collections, function($a, $b) use($collator){ return $collator->compare($a->LabelSort, $b->LabelSort); });

		apcu_store('bulk-downloads-collections', $collections, 43200); // 12 hours

		// Generate bulk downloads by authors
		foreach(glob(WEB_ROOT . '/bulk-downloads/authors/*/', GLOB_NOSORT) as $dir){
			$authors[] = self::FillBulkDownloadObject($dir, 'authors', '/ebooks');
		}
		usort($authors, function($a, $b) use($collator){ return $collator->compare($a->LabelSort, $b->LabelSort); });

		apcu_store('bulk-downloads-authors', $authors, 43200); // 12 hours

		return ['months' => $months, 'subjects' => $subjects, 'collections' => $collections, 'authors' => $authors];
	}

	/**
	 * @return array<string, array<int|string, array<int|string, mixed>>>
	 */
	public static function RebuildFeedsCache(?string $returnType = null, ?string $returnClass = null): ?array{
		$feedTypes = ['opds', 'atom', 'rss'];
		$feedClasses = ['authors', 'collections', 'subjects'];
		$retval = null;
		$collator = Collator::create('en_US'); // Used for sorting letters with diacritics like in author names
		if($collator === null){
			throw new Exceptions\AppException('Couldn\'t create collator object when rebuilding feeds cache.');
		}

		foreach($feedTypes as $type){
			foreach($feedClasses as $class){
				$files = glob(WEB_ROOT . '/feeds/' . $type . '/' . $class . '/*.xml');

				$feeds = [];

				foreach($files as $file){
					$obj = new stdClass();
					$obj->Url = '/feeds/' . $type . '/' . $class . '/' . basename($file, '.xml');

					$obj->Label  = exec('attr -g se-label ' . escapeshellarg($file)) ?: null;
					if($obj->Label == null){
						$obj->Label = basename($file, '.xml');
					}

					$obj->LabelSort  = exec('attr -g se-label-sort ' . escapeshellarg($file)) ?: null;
					if($obj->LabelSort == null){
						$obj->LabelSort = basename($file, '.xml');
					}

					$feeds[] = $obj;
				}

				usort($feeds, function($a, $b) use($collator){ return $collator->compare($a->LabelSort, $b->LabelSort); });

				if($type == $returnType && $class == $returnClass){
					$retval = $feeds;
				}

				apcu_store('feeds-index-' . $type . '-' . $class, $feeds);
			}
		}

		return $retval;
	}

	public static function GetEbook(?string $ebookWwwFilesystemPath): ?Ebook{
		if($ebookWwwFilesystemPath === null){
			return null;
		}

		$result = self::GetFromApcu('ebook-' . $ebookWwwFilesystemPath);

		if(sizeof($result) > 0){
			return $result[0];
		}
		else{
			return null;
		}
	}

	public static function RebuildCache(): void{
		// We check a lockfile because this can be a long-running command.
		// We don't want to queue up a bunch of these in case someone is refreshing the index constantly.
		$lockVar = 'library-cache-rebuilding';
		try{
			$val = apcu_fetch($lockVar);
			return;
		}
		catch(Safe\Exceptions\ApcuException){
			apcu_store($lockVar, true);
		}

		$collator = Collator::create('en_US'); // Used for sorting letters with diacritics like in author names
		if($collator === null){
			throw new Exceptions\AppException('Couldn\'t create collator object when rebuilding cache.');
		}

		$ebooks = [];
		$ebooksByCollection = [];
		$ebooksByTag = [];
		$collectionsByName = [];
		$authors = [];
		$tagsByName = [];

		foreach(explode("\n", trim(shell_exec('find ' . EBOOKS_DIST_PATH . ' -name "content.opf"'))) as $filename){
			try{
				$ebookWwwFilesystemPath = preg_replace('|/content\.opf|ius', '', $filename);

				$ebook = new Ebook($ebookWwwFilesystemPath);

				$ebooks[$ebookWwwFilesystemPath] = $ebook;

				// Create the collections cache
				foreach($ebook->Collections as $collection){
					$urlSafeCollection = Formatter::MakeUrlSafe($collection->Name);
					if(!array_key_exists($urlSafeCollection, $ebooksByCollection)){
						$ebooksByCollection[$urlSafeCollection] = [];
						$collectionsByName[$urlSafeCollection] = $collection;
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
					$ebooksByCollection[$urlSafeCollection][] = $sortItem;
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
			catch(\Exception){
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
		foreach($ebooksByCollection as $collection => $sortItems){
			// Sort the array by the ebook's ordinal in the collection. We use this custom sort function
			// because an ebook may share the same place in a collection with another ebook; see above.
			usort($sortItems, function($a, $b){
				if($a->Ordinal == $b->Ordinal){
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

		apcu_delete('collections');
		usort($collectionsByName, function($a, $b) use($collator){ return $collator->compare($a->GetSortedName(), $b->GetSortedName()); });
		apcu_store('collections', $collectionsByName);

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

		apcu_store('is-cache-fresh', true);
	}

	/**
	 * @return array<Artist>
	 */
	public static function GetAllArtists(): array{
		return Db::Query('
			SELECT *
			from Artists
			order by Name asc', [], 'Artist');
	}
}
