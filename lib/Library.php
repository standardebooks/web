<?
class Library{
	public static function GetEbooks(string $sort = null): array{
		$ebooks = [];

		switch($sort){
			case SORT_AUTHOR_ALPHA:
				// Get all ebooks, sorted by author alpha first.
				$ebooks = apcu_fetch('ebooks-alpha', $success);

				if(!$success){
					$ebooks = Library::GetEbooks();

					usort($ebooks, function($a, $b){
						return strcmp(mb_strtolower($a->Authors[0]->SortName), mb_strtolower($b->Authors[0]->SortName));
					});

					apcu_store('ebooks-alpha', $ebooks);
				}
				break;

			case SORT_NEWEST:
				// Get all ebooks, sorted by newest first.
				$ebooks = apcu_fetch('ebooks-newest', $success);

				if(!$success){
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
				$ebooks = apcu_fetch('ebooks', $success);

				if(!$success){
					foreach(explode("\n", trim(shell_exec('find ' . SITE_ROOT . '/www/ebooks/ -name "content.opf"') ?? '')) as $filename){
						$ebookWwwFilesystemPath = preg_replace('|/src/.+|ius', '', $filename) ?: '';
						$ebook = apcu_fetch('ebook-' . $ebookWwwFilesystemPath, $success);

						if(!$success){
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
		$ebooks = apcu_fetch('author-' . $wwwFilesystemPath, $success);

		if(!$success){
			$ebooks = [];

			foreach(explode("\n", trim(shell_exec('find ' . escapeshellarg($wwwFilesystemPath) . ' -name "content.opf"') ?? '')) as $filename){
				try{
					$ebookWwwFilesystemPath = preg_replace('|/src/.+|ius', '', $filename) ?? '';
					$ebook = apcu_fetch('ebook-' . $ebookWwwFilesystemPath, $success);

					if(!$success){
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
?>
