<?
use Safe\DateTimeImmutable;

use function Safe\apcu_fetch;
use function Safe\exec;
use function Safe\filemtime;
use function Safe\filesize;
use function Safe\glob;
use function Safe\ksort;
use function Safe\preg_replace;
use function Safe\preg_split;
use function Safe\shell_exec;
use function Safe\sleep;
use function Safe\usort;

class Library{
	/**
	* @param array<string> $tags
	* @return array{ebooks: array<Ebook>, ebooksCount: int}
	*/
	public static function FilterEbooks(string $query = null, array $tags = [], EbookSortType $sort = null, int $page = 1, int $perPage = EBOOKS_PER_PAGE): array{
		$limit = $perPage;
		$offset = (($page - 1) * $perPage);
		$joinContributors = '';
		$joinTags = '';
		$params = [];
		$whereCondition = 'where true';

		$orderBy = 'e.EbookCreated desc';
		if($sort == EbookSortType::AuthorAlpha){
			$joinContributors = 'inner join Contributors con using (EbookId)';
			$whereCondition .= ' AND con.MarcRole = "aut"';
			$orderBy = 'con.SortName, e.EbookCreated desc';
		}
		elseif($sort == EbookSortType::ReadingEase){
			$orderBy = 'e.ReadingEase desc';
		}
		elseif($sort == EbookSortType::Length){
			$orderBy = 'e.WordCount';
		}

		if(sizeof($tags) > 0 && !in_array('all', $tags)){ // 0 tags means "all ebooks"
			$joinTags = 'inner join EbookTags et using (EbookId)
					inner join Tags t using (TagId)';
			$whereCondition .= ' AND t.Name in ' . Db::CreateSetSql($tags) . ' ';
			$params = $tags;
		}

		if($query !== null && $query != ''){
			$query = trim(preg_replace('|[^a-zA-Z0-9 ]|ius', ' ', Formatter::RemoveDiacritics($query)));
			$whereCondition .= ' AND match(e.IndexableText) against(?) ';
			$params[] = $query;
		}

		$ebooksCount = Db::QueryInt('
				SELECT count(distinct e.EbookId)
				from Ebooks e
				' . $joinContributors . '
				' . $joinTags . '
				' . $whereCondition . '
				', $params);

		$params[] = $limit;
		$params[] = $offset;

		$ebooks = Db::Query('
				SELECT distinct e.*
				from Ebooks e
				' . $joinContributors . '
				' . $joinTags . '
				' . $whereCondition . '
				order by ' . $orderBy . '
				limit ?
				offset ?', $params, Ebook::class);

		return ['ebooks' => $ebooks, 'ebooksCount' => $ebooksCount];
	}

	/**
	 * @return array<Ebook>
	 * @throws Exceptions\AppException
	 */
	public static function GetEbooks(): array{
		// Get all ebooks, unsorted.
		/** @var array<Ebook> */
		return self::GetFromApcu('ebooks');
	}

	/**
	 * @return array<Ebook>
	 */
	public static function GetEbooksByAuthor(string $urlPath): array{
		if(mb_strpos($urlPath, '_') === false){
			// Single author
			return Db::Query('
					SELECT e.*
					from Ebooks e
					inner join Contributors con using (EbookId)
					where con.MarcRole = "aut"
					    and con.UrlName = ?
					order by e.EbookCreated desc
				', [$urlPath], Ebook::class);
		}
		else{
			// Multiple authors, e.g., karl-marx_friedrich-engels
			$authors = explode('_', $urlPath);

			$params = $authors;
			$params[] = sizeof($authors); // The number of authors in the URL must match the number of Contributor records.

			return Db::Query('
					SELECT e.*
					from Ebooks e
					inner join Contributors con using (EbookId)
					where con.MarcRole = "aut"
					    and con.UrlName in ' . Db::CreateSetSql($authors)  . '
					group by e.EbookId
					having count(distinct con.UrlName) = ?
					order by e.EbookCreated desc
				', $params, Ebook::class);
		}
	}

	/**
	 * @return array<Collection>
	 * @throws Exceptions\AppException
	 */
	public static function GetEbookCollections(): array{
		$collections = Db::Query('
					SELECT *
					from Collections
				', [], Collection::class);

		$collator = Collator::create('en_US');
		if($collator === null){
			throw new Exceptions\AppException('Couldn\'t create collator object when getting collections.');
		}
		usort($collections, function($a, $b) use($collator){ return $collator->compare($a->GetSortedName(), $b->GetSortedName()); });
		return $collections;
	}

	/**
	 * @return array<Ebook>
	 */
	public static function GetEbooksByCollection(string $collection): array{
		$ebooks = Db::Query('
				SELECT e.*
				from Ebooks e
				inner join CollectionEbooks ce using (EbookId)
				inner join Collections c using (CollectionId)
				where c.UrlName = ?
				order by ce.SequenceNumber, e.EbookCreated desc
				', [$collection], Ebook::class);

		return $ebooks;
	}

	/**
	 * @return array<Ebook>
	 */
	public static function GetRelatedEbooks(Ebook $ebook, int $count, ?EbookTag $relatedTag): array{
		if($relatedTag !== null){
			$relatedEbooks = Db::Query('
						SELECT e.*
						from Ebooks e
						inner join EbookTags et using (EbookId)
						where et.TagId = ?
						    and et.EbookId != ?
						order by RAND()
						limit ?
				', [$relatedTag->TagId, $ebook->EbookId, $count], Ebook::class);
		}
		else{
			$relatedEbooks = Db::Query('
						SELECT *
						from Ebooks
						where EbookId != ?
						order by RAND()
						limit ?
				', [$ebook->EbookId, $count], Ebook::class);
		}

		return $relatedEbooks;
	}

	/**
	 * @return array<EbookTag>
	 */
	public static function GetTags(): array{
		$tags = Db::Query('
				SELECT *
				from Tags t
				where Type = "ebook"
				order by Name
			', [], EbookTag::class);

		return $tags;
	}

	/**
	* @return array{artworks: array<Artwork>, artworksCount: int}
	*/
	public static function FilterArtwork(?string $query = null, ?string $status = null, ?ArtworkSortType $sort = null, ?int $submitterUserId = null, int $page = 1, int $perPage = ARTWORK_PER_PAGE): array{
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
			$params[] = ArtworkStatusType::Approved->value;
		}
		elseif($status == 'all-admin'){
			$statusCondition = 'true';
		}
		elseif($status == 'all-submitter' && $submitterUserId !== null){
			$statusCondition = '(Status = ? or (Status = ? and SubmitterUserId = ?))';
			$params[] = ArtworkStatusType::Approved->value;
			$params[] = ArtworkStatusType::Unverified->value;
			$params[] = $submitterUserId;
		}
		elseif($status == 'unverified-submitter' && $submitterUserId !== null){
			$statusCondition = 'Status = ? and SubmitterUserId = ?';
			$params[] = ArtworkStatusType::Unverified->value;
			$params[] = $submitterUserId;
		}
		elseif($status == 'in-use'){
			$statusCondition = 'Status = ? and EbookUrl is not null';
			$params[] = ArtworkStatusType::Approved->value;
		}
		elseif($status == ArtworkStatusType::Approved->value){
			$statusCondition = 'Status = ? and EbookUrl is null';
			$params[] = ArtworkStatusType::Approved->value;
		}
		else{
			$statusCondition = 'Status = ?';
			$params[] = $status;
		}

		$orderBy = 'art.Created desc';
		if($sort == ArtworkSortType::ArtistAlpha){
			$orderBy = 'a.Name';
		}
		elseif($sort == ArtworkSortType::CompletedNewest){
			$orderBy = 'art.CompletedYear desc';
		}

		// Remove diacritics and non-alphanumeric characters, but preserve apostrophes
		if($query !== null && $query != ''){
			$query = trim(preg_replace('|[^a-zA-Z0-9\'’ ]|ius', ' ', Formatter::RemoveDiacritics($query)));
		}
		else{
			$query = '';
		}

		// We use replace() below because if there's multiple contributors separated by an underscore,
		// the underscore won't count as word boundary and we won't get a match.
		// See https://github.com/standardebooks/web/pull/325
		$limit = $perPage;
		$offset = (($page - 1) * $perPage);

		if($query == ''){
			$artworksCount = Db::QueryInt('
				SELECT count(*)
				from Artworks art
				where ' . $statusCondition, $params);

			$params[] = $limit;
			$params[] = $offset;

			$artworks = Db::Query('
				SELECT art.*
				from Artworks art
				inner join Artists a USING (ArtistId)
				where ' . $statusCondition . '
				order by ' . $orderBy . '
				limit ?
				offset ?', $params, Artwork::class);
		}
		else{
			// Split the query on word boundaries followed by spaces. This keeps words with apostrophes intact.
			$tokenArray = preg_split('/\b\s+/', $query, -1, PREG_SPLIT_NO_EMPTY);

			// Join the tokens with '|' to search on any token, but add word boundaries to force the full token to match
			$tokenizedQuery = '\b(' . implode('|', $tokenArray) . ')\b';

			$params[] = $tokenizedQuery; // art.Name
			$params[] = $tokenizedQuery; // art.EbookUrl
			$params[] = $tokenizedQuery; // a.Name
			$params[] = $tokenizedQuery; // aan.Name
			$params[] = $tokenizedQuery; // t.Name

			$artworksCount = Db::QueryInt('
				SELECT
				    count(*)
				from
				    (SELECT distinct
				        ArtworkId
				    from
				        Artworks art
				    inner join Artists a USING (ArtistId)
				    left join ArtistAlternateNames aan USING (ArtistId)
				    left join ArtworkTags at USING (ArtworkId)
				    left join Tags t USING (TagId)
				    where
				        ' . $statusCondition . '
				            and (art.Name regexp ?
				            or replace(art.EbookUrl, "_", " ") regexp ?
				            or a.Name regexp ?
				            or aan.Name regexp ?
				            or t.Name regexp ?)
				    group by art.ArtworkId) x', $params);

			$params[] = $limit;
			$params[] = $offset;

			$artworks = Db::Query('
				SELECT art.*
				from Artworks art
				  inner join Artists a using (ArtistId)
				  left join ArtistAlternateNames aan using (ArtistId)
				  left join ArtworkTags at using (ArtworkId)
				  left join Tags t using (TagId)
				where ' . $statusCondition . '
				  and (art.Name regexp ?
				  or replace(art.EbookUrl, "_", " ") regexp ?
				  or a.Name regexp ?
				  or aan.Name regexp ?
				  or t.Name regexp ?)
				group by art.ArtworkId
				order by ' . $orderBy . '
				limit ?
				offset ?', $params, Artwork::class);
		}

		return ['artworks' => $artworks, 'artworksCount' => $artworksCount];
	}

	/**
	 * @return array<Artwork>
	 * @throws Exceptions\ArtistNotFoundException
	 */
	public static function GetArtworksByArtist(?string $artistUrlName, ?string $status, ?int $submitterUserId): array{
		if($artistUrlName === null){
			throw new Exceptions\ArtistNotFoundException();
		}

		// $status is only one of three special statuses, which are a subset of FilterArtwork() above:
		// null: same as "all"
		// "all": Show all approved and in use artwork
		// "all-admin": Show all artwork regardless of status
		// "all-submitter": Show all approved and in use artwork, plus unverified artwork from the submitter
		$statusCondition = '';
		$params = [];

		if($status == 'all-admin'){
			$statusCondition = 'true';
		}
		elseif($status == 'all-submitter' && $submitterUserId !== null){
			$statusCondition = '(Status = ? or (Status = ? and SubmitterUserId = ?))';
			$params[] = ArtworkStatusType::Approved->value;
			$params[] = ArtworkStatusType::Unverified->value;
			$params[] = $submitterUserId;
		}
		else{
			$statusCondition = 'Status = ?';
			$params[] = ArtworkStatusType::Approved->value;
		}

		$params[] = $artistUrlName; // a.UrlName

		$artworks = Db::Query('
			SELECT art.*
			from Artworks art
			  inner join Artists a using (ArtistId)
			where ' . $statusCondition . '
			and a.UrlName = ?
			order by art.Created desc', $params, Artwork::class);

		return $artworks;
	}


	/**
	 * @return array<mixed>
	 * @throws Exceptions\AppException
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
	public static function GetEbooksFromFilesystem(?string $webRoot = WEB_ROOT): array{
		$ebooks = [];

		$contentFiles = explode("\n", trim(shell_exec('find ' . escapeshellarg($webRoot . '/ebooks/') . ' -name "content.opf" | sort')));

		foreach($contentFiles as $path){
			if($path == '')
				continue;

			$ebookWwwFilesystemPath = '';

			try{
				$ebookWwwFilesystemPath = preg_replace('|/content\.opf|ius', '', $path);

				$ebooks[] = Ebook::FromFilesystem($ebookWwwFilesystemPath);
			}
			catch(\Exception){
				// An error in a book isn't fatal; just carry on.
			}
		}

		return $ebooks;
	}

	private static function FillBulkDownloadObject(string $dir, string $downloadType, string $urlRoot): stdClass{
		$obj = new stdClass();

		/** @throws void */
		$now = new DateTimeImmutable();

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
	 * @return array<string, array<int|string, array<int|string, stdClass>>>
	 * @throws Exceptions\AppException
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

			try{
				$date = new DateTimeImmutable($obj->Label . '-01');
			}
			catch(\Exception){
				throw new Exceptions\AppException('Couldn\'t parse date on bulk download object.');
			}
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
	 * @throws Exceptions\AppException
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

	/**
	 * @throws Exceptions\AppException
	 */
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

				$ebook = Ebook::FromFilesystem($ebookWwwFilesystemPath);

				$ebooks[$ebookWwwFilesystemPath] = $ebook;

				// Create the collections cache
				foreach($ebook->CollectionMemberships as $collectionMembership){
					$collection = $collectionMembership->Collection;
					$sequenceNumber = $collectionMembership->SequenceNumber;
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
					if($sequenceNumber !== null){
						$sortItem->Ordinal = $sequenceNumber;
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
	public static function GetArtists(): array{
		return Db::Query('
			SELECT *
			from Artists
			order by Name asc', [], Artist::class);
	}
}
