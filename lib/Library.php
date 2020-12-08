<?
use function Safe\apcu_fetch;
use function Safe\ksort;
use function Safe\preg_replace;
use function Safe\touch;
use function Safe\unlink;
use function Safe\usort;

class Library{
	/**
	 * @return array<Ebook>
	 */
	public static function FilterEbooks($query = null, $tags = [], $sort = null){
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
		$ebooks = [];

		// Get all ebooks, unsorted.
		try{
			$ebooks = apcu_fetch('ebooks');
		}
		catch(Safe\Exceptions\ApcuException $ex){
			Library::RebuildCache();
			$ebooks = apcu_fetch('ebooks');
		}

		return $ebooks;
	}

	/**
	 * @return array<Ebook>
	 */
	public static function GetEbooksByAuthor(string $wwwFilesystemPath): array{
		// Do we have the author's ebooks cached?
		$ebooks = [];

		try{
			$ebooks = apcu_fetch('author-' . $wwwFilesystemPath);
		}
		catch(Safe\Exceptions\ApcuException $ex){
		}

		return $ebooks;
	}

	/**
	 * @return array<Ebook>
	 */
	public static function GetEbooksByTag(string $tag): array{
		// Do we have the tag's ebooks cached?
		$ebooks = [];

		try{
			$ebooks = apcu_fetch('tag-' . $tag);
		}
		catch(Safe\Exceptions\ApcuException $ex){
		}

		return $ebooks;
	}

	/**
	 * @return array<Ebook>
	 */
	public static function GetEbooksByCollection(string $collection): array{
		// Do we have the tag's ebooks cached?
		$ebooks = [];

		try{
			$ebooks = apcu_fetch('collection-' . $collection);
		}
		catch(Safe\Exceptions\ApcuException $ex){
		}

		return $ebooks;
	}

	public static function GetTags(): array{
		return apcu_fetch('tags');
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

					if($collection->SequenceNumber !== null){
						$collections[$urlSafeCollection][$collection->SequenceNumber] = $ebook;
					}
					else{
						$collections[$urlSafeCollection][] = $ebook;
					}
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
		foreach($collections as $collection => $ebooks){
			// Sort the array by key, then reindex to 0 with array_values
			ksort($ebooks);
			apcu_store('collection-' . $collection, array_values($ebooks));
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
