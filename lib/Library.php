<?
use function Safe\apcu_fetch;
use function Safe\preg_replace;
use function Safe\touch;
use function Safe\unlink;
use function Safe\usort;

class Library{
	public static function GetEbooks(string $sort = null): array{
		$ebooks = [];

		switch($sort){
			case SORT_AUTHOR_ALPHA:
				// Get all ebooks, sorted by author alpha first.
				try{
					$ebooks = apcu_fetch('ebooks-alpha');
				}
				catch(Safe\Exceptions\ApcuException $ex){
					Library::RebuildCache();
					$ebooks = apcu_fetch('ebooks-alpha');
				}
				break;

			case SORT_NEWEST:
				// Get all ebooks, sorted by release date first.
				try{
					$ebooks = apcu_fetch('ebooks-newest');
				}
				catch(Safe\Exceptions\ApcuException $ex){
					Library::RebuildCache();
					$ebooks = apcu_fetch('ebooks-newest');
				}
				break;

			case SORT_READING_EASE:
				// Get all ebooks, sorted by easiest first.
				try{
					$ebooks = apcu_fetch('ebooks-reading-ease');
				}
				catch(Safe\Exceptions\ApcuException $ex){
					Library::RebuildCache();
					$ebooks = apcu_fetch('ebooks-reading-ease');
				}
				break;

			case SORT_LENGTH:
				// Get all ebooks, sorted by fewest words first.
				try{
					$ebooks = apcu_fetch('ebooks-length');
				}
				catch(Safe\Exceptions\ApcuException $ex){
					Library::RebuildCache();
					$ebooks = apcu_fetch('ebooks-length');
				}
				break;

			default:
				// Get all ebooks, unsorted.
				try{
					$ebooks = apcu_fetch('ebooks');
				}
				catch(Safe\Exceptions\ApcuException $ex){
					Library::RebuildCache();
					$ebooks = apcu_fetch('ebooks');
				}
				break;
		}

		return $ebooks;
	}

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

		foreach(explode("\n", trim(shell_exec('find ' . EBOOKS_DIST_PATH . ' -name "content.opf"') ?? '')) as $filename){
			try{
				$ebookWwwFilesystemPath = preg_replace('|/src/.+|ius', '', $filename) ?? '';

				$ebook = new Ebook($ebookWwwFilesystemPath);

				$ebooks[$ebookWwwFilesystemPath] = $ebook;

				// Create the collections cache
				foreach($ebook->Collections as $collection){
					$lcCollection = strtolower(Formatter::RemoveDiacritics($collection->Name));
					if(!array_key_exists($lcCollection, $collections)){
						$collections[$lcCollection] = [];
					}

					$collections[$lcCollection][] = $ebook;
				}

				// Create the tags cache
				foreach($ebook->Tags as $tag){
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

		// Sort ebooks by release date, then save
		usort($ebooks, function($a, $b){
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

		$ebooks = array_reverse($ebooks);

		apcu_delete('ebooks-newest');
		apcu_store('ebooks-newest', $ebooks);

		// Sort ebooks by title alpha, then save
		usort($ebooks, function($a, $b){
			return strcmp(mb_strtolower($a->Authors[0]->SortName), mb_strtolower($b->Authors[0]->SortName));
		});

		apcu_delete('ebooks-alpha');
		apcu_store('ebooks-alpha', $ebooks);

		// Sort ebooks by reading ease, then save
		usort($ebooks, function($a, $b){
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

		$ebooks = array_reverse($ebooks);

		apcu_delete('ebooks-reading-ease');
		apcu_store('ebooks-reading-ease', $ebooks);

		// Sort ebooks by word count, then save
		usort($ebooks, function($a, $b){
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

		apcu_delete('ebooks-length');
		apcu_store('ebooks-length', $ebooks);

		// Now store various collections
		apcu_delete(new APCUIterator('/^collection-/'));
		foreach($collections as $collection => $ebooks){
			apcu_store('collection-' . $collection, $ebooks);
		}

		apcu_delete(new APCUIterator('/^tag-/'));
		foreach($tags as $tag => $ebooks){
			apcu_store('tag-' . $tag, $ebooks);
		}

		apcu_delete(new APCUIterator('/^author-/'));
		foreach($authors as $author => $ebooks){
			apcu_store('author-' . $author, $ebooks);
		}

		apcu_delete($lockVar);
	}
}
