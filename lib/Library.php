<?
use function Safe\apcu_fetch;
use function Safe\preg_replace;
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
					$ebooks = Library::GetEbooks();

					usort($ebooks, function($a, $b){
						return strcmp(mb_strtolower($a->Authors[0]->SortName), mb_strtolower($b->Authors[0]->SortName));
					});

					apcu_store('ebooks-alpha', $ebooks);
				}
				break;

			case SORT_NEWEST:
				// Get all ebooks, sorted by newest first.
				try{
					$ebooks = apcu_fetch('ebooks-newest');
				}
				catch(Safe\Exceptions\ApcuException $ex){
					$ebooks = Library::GetEbooks();

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

					apcu_store('ebooks-newest', $ebooks);
				}
				break;

			default:
				// Get all ebooks, unsorted.
				try{
					$ebooks = apcu_fetch('ebooks');
				}
				catch(Safe\Exceptions\ApcuException $ex){
					foreach(explode("\n", trim(shell_exec('find ' . SITE_ROOT . '/www/ebooks/ -name "content.opf"') ?? '')) as $filename){
						$ebookWwwFilesystemPath = preg_replace('|/src/.+|ius', '', $filename) ?: '';
						try{
							$ebook = apcu_fetch('ebook-' . $ebookWwwFilesystemPath);
						}
						catch(Safe\Exceptions\ApcuException $ex){
							$ebook = new Ebook($ebookWwwFilesystemPath);
							apcu_store('ebook-' . $ebookWwwFilesystemPath, $ebook);
						}

						$ebooks[] = $ebook;
					}

					apcu_store('ebooks', $ebooks);
				}
				break;
		}

		return $ebooks;
	}

	public static function GetEbooksByAuthor(string $wwwFilesystemPath): array{
		// Do we have the author's ebooks cached?
		try{
			$ebooks = apcu_fetch('author-' . $wwwFilesystemPath);
		}
		catch(Safe\Exceptions\ApcuException $ex){
			$ebooks = [];

			foreach(explode("\n", trim(shell_exec('find ' . escapeshellarg($wwwFilesystemPath) . ' -name "content.opf"') ?? '')) as $filename){
				try{
					$ebookWwwFilesystemPath = preg_replace('|/src/.+|ius', '', $filename) ?? '';
					try{
						$ebook = apcu_fetch('ebook-' . $ebookWwwFilesystemPath);
					}
					catch(Safe\Exceptions\ApcuException $ex){
						$ebook = new Ebook($ebookWwwFilesystemPath);
						apcu_store('ebook-' . $ebookWwwFilesystemPath, $ebook);
					}

					$ebooks[] = $ebook;

				}
				catch(\Exception $ex){
					// An error in a book isn't fatal; just carry on.
				}
			}

			apcu_store('author-' . $wwwFilesystemPath, $ebooks);
		}

		return $ebooks;
	}

	public static function GetEbooksByTag(string $tag): array{
		// Do we have the tag's ebooks cached?
		try{
			$ebooks = apcu_fetch('tag-' . $tag);
		}
		catch(Safe\Exceptions\ApcuException $ex){
			$ebooks = [];

			foreach(explode("\n", trim(shell_exec('find ' . SITE_ROOT . '/www/ebooks/ -name "content.opf"') ?? '')) as $filename){
				try{
					$ebookWwwFilesystemPath = preg_replace('|/src/.+|ius', '', $filename) ?? '';
					try{
						$ebook = apcu_fetch('ebook-' . $ebookWwwFilesystemPath);
					}
					catch(Safe\Exceptions\ApcuException $ex){
						$ebook = new Ebook($ebookWwwFilesystemPath);
						apcu_store('ebook-' . $ebookWwwFilesystemPath, $ebook);
					}

					if($ebook->HasTag($tag)){
						$ebooks[] = $ebook;
					}
				}
				catch(\Exception $ex){
					// An error in a book isn't fatal; just carry on.
				}
			}

			apcu_store('tag-' . $tag, $ebooks);
		}

		return $ebooks;
	}

	public static function GetEbooksByCollection(string $collection): array{
		// Do we have the tag's ebooks cached?
		try{
			$ebooks = apcu_fetch('collection-' . $collection);
		}
		catch(Safe\Exceptions\ApcuException $ex){
			$ebooks = [];

			foreach(explode("\n", trim(shell_exec('find ' . SITE_ROOT . '/www/ebooks/ -name "content.opf"') ?? '')) as $filename){
				try{
					$ebookWwwFilesystemPath = preg_replace('|/src/.+|ius', '', $filename) ?? '';
					try{
						$ebook = apcu_fetch('ebook-' . $ebookWwwFilesystemPath);
					}
					catch(Safe\Exceptions\ApcuException $ex){
						$ebook = new Ebook($ebookWwwFilesystemPath);
						apcu_store('ebook-' . $ebookWwwFilesystemPath, $ebook);
					}

					if($ebook->IsInCollection($collection)){
						$ebooks[] = $ebook;
					}
				}
				catch(\Exception $ex){
					// An error in a book isn't fatal; just carry on.
				}
			}

			apcu_store('collection-' . $collection, $ebooks);
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
}
