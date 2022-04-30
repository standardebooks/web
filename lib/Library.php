<?
use function Safe\apcu_fetch;
use function Safe\natsort;
use function Safe\preg_replace;
use function Safe\sleep;
use function Safe\usort;

class Library{
	/**
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
					if($a->Timestamp < $b->Timestamp){
						return -1;
					}
					elseif($a->Timestamp == $b->Timestamp){
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
		$tags = [];
		$authors = [];
		$tagsByName = [];

		foreach(explode("\n", trim(shell_exec('find ' . EBOOKS_DIST_PATH . ' -name "content.opf"') ?? '')) as $filename){
			try{
				$ebookWwwFilesystemPath = preg_replace('|/content\.opf|ius', '', $filename) ?? '';

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
					$tagsByName[] = $tag->Name;
					$lcTag = strtolower($tag->Name);
					if(!array_key_exists($lcTag, $tags)){
						$tags[$lcTag] = [];
					}

					$tags[$lcTag][] = $ebook;
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
		foreach($tags as $tag => $ebooks){
			apcu_store('tag-' . $tag, $ebooks);
		}

		apcu_delete('tags');
		$tagsByName = array_unique($tagsByName, SORT_STRING);
		natsort($tagsByName);
		apcu_store('tags', $tagsByName);

		apcu_delete(new APCUIterator('/^author-/'));
		foreach($authors as $author => $ebooks){
			apcu_store('author-' . $author, $ebooks);
		}

		apcu_delete($lockVar);
	}
}
