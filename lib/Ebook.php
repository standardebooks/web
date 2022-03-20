<?
use Safe\DateTime;
use function Safe\file_get_contents;
use function Safe\json_encode;
use function Safe\glob;
use function Safe\preg_match;
use function Safe\preg_replace;
use function Safe\substr;

class Ebook{
	public $WwwFilesystemPath;
	public $RepoFilesystemPath;
	public $Url;
	public $KindleCoverUrl;
	public $EpubUrl;
	public $AdvancedEpubUrl;
	public $KepubUrl;
	public $Azw3Url;
	public $HasDownloads;
	public $GitCommits = [];
	public $Tags = [];
	public $LocTags = [];
	public $Collections = [];
	public $Identifier;
	public $UrlSafeIdentifier;
	public $HeroImageUrl;
	public $HeroImageAvifUrl;
	public $HeroImage2xUrl;
	public $HeroImage2xAvifUrl;
	public $CoverImageUrl;
	public $CoverImageAvifUrl;
	public $CoverImage2xUrl;
	public $CoverImage2xAvifUrl;
	public $DistCoverUrl;
	public $Title;
	public $FullTitle;
	public $AlternateTitle;
	public $Description;
	public $LongDescription;
	public $Language;
	public $WordCount;
	public $ReadingEase;
	public $ReadingEaseDescription;
	public $ReadingTime;
	public $GitHubUrl;
	public $WikipediaUrl;
	public $Sources = [];
	public $Authors = []; // Array of Contributors
	public $AuthorsHtml;
	public $AuthorsUrl; // This is a single URL even if there are multiple authors; for example, /ebooks/karl-marx_friedrich-engels/
	public $Illustrators = []; // Array of Contributors
	public $Translators = []; // Array of Contributors
	public $Contributors = []; // Array of Contributors
	public $ContributorsHtml;
	public $TitleWithCreditsHtml = '';
	public $Timestamp;
	public $ModifiedTimestamp;
	public $TextUrl;
	public $TextSinglePageUrl;
	public $TocEntries = null; // A list of non-Roman ToC entries ONLY IF the work has the 'shorts' or 'poetry' tag, null otherwise

	public function __construct(string $wwwFilesystemPath){
		// First, construct a source repo path from our WWW filesystem path.
		$this->RepoFilesystemPath = str_replace(EBOOKS_DIST_PATH, '', $wwwFilesystemPath);
		$this->RepoFilesystemPath = SITE_ROOT . '/ebooks/' . str_replace('/', '_', $this->RepoFilesystemPath) . '.git';

		if(!is_dir($this->RepoFilesystemPath)){ // On dev systems we might not have the bare repos, so make an adjustment
			$this->RepoFilesystemPath = preg_replace('/\.git$/ius', '', $this->RepoFilesystemPath) ?? '';
		}

		if(!is_dir($wwwFilesystemPath)){
			throw new Exceptions\InvalidEbookException('Invalid www filesystem path: ' . $wwwFilesystemPath);
		}

		if(!is_dir($this->RepoFilesystemPath)){
			throw new Exceptions\InvalidEbookException('Invalid repo filesystem path: ' . $this->RepoFilesystemPath);
		}

		if(!is_file($wwwFilesystemPath . '/content.opf')){
			throw new Exceptions\InvalidEbookException('Invalid content.opf file: ' . $wwwFilesystemPath . '/content.opf');
		}

		$this->WwwFilesystemPath = $wwwFilesystemPath;
		$this->Url = str_replace(WEB_ROOT, '', $this->WwwFilesystemPath);

		$rawMetadata = file_get_contents($wwwFilesystemPath . '/content.opf') ?: '';

		// Get the SE identifier.
		preg_match('|<dc:identifier[^>]*?>(.+?)</dc:identifier>|ius', $rawMetadata, $matches);
		if(sizeof($matches) != 2){
			throw new Exceptions\EbookParsingException('Invalid <dc:identifier> element.');
		}
		$this->Identifier = (string)$matches[1];

		$this->UrlSafeIdentifier = str_replace(['url:https://standardebooks.org/ebooks/', '/'], ['', '_'], $this->Identifier);

		$this->TextUrl = $this->Url . '/text';

		$tempPath = glob($this->WwwFilesystemPath . '/text/single-page.xhtml');
		if(sizeof($tempPath) > 0){
			$this->TextSinglePageUrl = $this->Url . '/text/single-page';
		}

		// Generate the Kindle cover URL.
		$tempPath = glob($this->WwwFilesystemPath . '/downloads/*_EBOK_portrait.jpg');
		if(sizeof($tempPath) > 0){
			$this->KindleCoverUrl = $this->Url . '/downloads/' . basename($tempPath[0]);
		}

		// Generate the compatible epub URL.
		$tempPath = glob($this->WwwFilesystemPath . '/downloads/*.epub');
		if(sizeof($tempPath) > 0){
			$this->EpubUrl = $this->Url . '/downloads/' . basename($tempPath[0]);
		}

		// Generate the epub URL
		$tempPath = glob($this->WwwFilesystemPath . '/downloads/*_advanced.epub');
		if(sizeof($tempPath) > 0){
			$this->AdvancedEpubUrl = $this->Url . '/downloads/' . basename($tempPath[0]);
		}

		// Generate the Kepub URL
		$tempPath = glob($this->WwwFilesystemPath . '/downloads/*.kepub.epub');
		if(sizeof($tempPath) > 0){
			$this->KepubUrl = $this->Url . '/downloads/' . basename($tempPath[0]);
		}

		// Generate the azw3 URL.
		$tempPath = glob($this->WwwFilesystemPath . '/downloads/*.azw3');
		if(sizeof($tempPath) > 0){
			$this->Azw3Url = $this->Url . '/downloads/' . basename($tempPath[0]);
		}

		$this->HasDownloads = $this->EpubUrl || $this->AdvancedEpubUrl || $this->KepubUrl || $this->Azw3Url;

		$tempPath = glob($this->WwwFilesystemPath . '/downloads/cover.jpg');
		if(sizeof($tempPath) > 0){
			$this->DistCoverUrl = $this->Url . '/downloads/' . basename($tempPath[0]);
		}

		// Fill in the short history of this repo.
		$historyEntries = explode("\n", shell_exec('cd ' . escapeshellarg($this->RepoFilesystemPath) . ' && git log -n5 --pretty=format:"%ct %H %s"') ?? '');

		foreach($historyEntries as $entry){
			$array = explode(' ', $entry, 3);
			$this->GitCommits[] = new GitCommit($array[0], $array[1], $array[2]);
		}

		// Get cover image URLs.
		$gitFolderPath = $this->RepoFilesystemPath;
		if(stripos($this->RepoFilesystemPath, '.git') === false){
			$gitFolderPath = $gitFolderPath . '/.git';
		}
		$hash = substr(sha1($this->GitCommits[0]->Timestamp->format('U') . ' ' . $this->GitCommits[0]->Message), 0, 8);
		$this->CoverImageUrl = '/images/covers/' . $this->UrlSafeIdentifier . '-' . $hash . '-cover.jpg';
		if(file_exists(WEB_ROOT . '/images/covers/' . $this->UrlSafeIdentifier . '-cover.avif')){
			$this->CoverImageAvifUrl = '/images/covers/' . $this->UrlSafeIdentifier . '-' . $hash . '-cover.avif';
		}
		$this->CoverImage2xUrl = '/images/covers/' . $this->UrlSafeIdentifier . '-' . $hash . '-cover@2x.jpg';
		if(file_exists(WEB_ROOT . '/images/covers/' . $this->UrlSafeIdentifier . '-cover@2x.avif')){
			$this->CoverImage2xAvifUrl = '/images/covers/' . $this->UrlSafeIdentifier . '-' . $hash . '-cover@2x.avif';
		}
		$this->HeroImageUrl = '/images/covers/' . $this->UrlSafeIdentifier . '-' . $hash . '-hero.jpg';
		if(file_exists(WEB_ROOT . '/images/covers/' . $this->UrlSafeIdentifier . '-hero.avif')){
			$this->HeroImageAvifUrl = '/images/covers/' . $this->UrlSafeIdentifier . '-' . $hash . '-hero.avif';
		}
		$this->HeroImage2xUrl = '/images/covers/' . $this->UrlSafeIdentifier . '-' . $hash . '-hero@2x.jpg';
		if(file_exists(WEB_ROOT . '/images/covers/' . $this->UrlSafeIdentifier . '-hero@2x.avif')){
			$this->HeroImage2xAvifUrl = '/images/covers/' . $this->UrlSafeIdentifier . '-' . $hash . '-hero@2x.avif';
		}

		// Now do some heavy XML lifting!
		$xml = new SimpleXMLElement(str_replace('xmlns=', 'ns=', $rawMetadata));
		$xml->registerXPathNamespace('dc', 'http://purl.org/dc/elements/1.1/');

		$this->Title = $this->NullIfEmpty($xml->xpath('/package/metadata/dc:title'));
		if($this->Title === null){
			throw new Exceptions\EbookParsingException('Invalid <dc:title> element.');
		}

		$this->Title = str_replace('\'', '’', $this->Title);

		$this->FullTitle = $this->NullIfEmpty($xml->xpath('/package/metadata/dc:title[@id="fulltitle"]'));

		$this->AlternateTitle = $this->NullIfEmpty($xml->xpath('/package/metadata/meta[@property="se:alternate-title"]'));

		$date = $xml->xpath('/package/metadata/dc:date');
		if($date !== false && sizeof($date) > 0){
			$this->Timestamp = new DateTime((string)$date[0]);
		}

		$modifiedDate = $xml->xpath('/package/metadata/meta[@property="dcterms:modified"]');
		if($modifiedDate !== false && sizeof($modifiedDate) > 0){
			$this->ModifiedTimestamp = new DateTime((string)$modifiedDate[0]);
		}

		// Get SE tags
		$includeToc = false;
		foreach($xml->xpath('/package/metadata/meta[@property="se:subject"]') ?: [] as $tag){
			$this->Tags[] = new Tag($tag);

			if($tag == 'Shorts' || $tag == 'Poetry'){
				$includeToc = true;
			}
		}

		// Fill the ToC if necessary
		if($includeToc){
			$this->TocEntries = [];
			$tocDom = new SimpleXMLElement(str_replace('xmlns=', 'ns=', file_get_contents($wwwFilesystemPath . '/toc.xhtml') ?: ''));
			$tocDom->registerXPathNamespace('epub', 'http://www.idpf.org/2007/ops');
			foreach($tocDom->xpath('/html/body//nav[@epub:type="toc"]//a[not(contains(@epub:type, "z3998:roman")) and not(text() = "Titlepage" or text() = "Imprint" or text() = "Colophon" or text() = "Endnotes" or text() = "Uncopyright") and not(contains(@href, "halftitle"))]') ?: [] as $item){
				$this->TocEntries[] = (string)$item;
			}
		}

		// Get SE collections
		foreach($xml->xpath('/package/metadata/meta[@property="belongs-to-collection"]') ?: [] as $collection){
			$c = new Collection($collection);
			foreach($xml->xpath('/package/metadata/meta[@refines="#' . $collection->attributes()->id . '"][@property="group-position"]') ?: [] as $s){
				$c->SequenceNumber = (int)$s;
			}
			foreach($xml->xpath('/package/metadata/meta[@refines="#' . $collection->attributes()->id . '"][@property="collection-type"]') ?: [] as $s){
				$c->Type = (string)$s;
			}
			$this->Collections[] = $c;
		}

		// Get LoC tags
		foreach($xml->xpath('/package/metadata/dc:subject') ?: [] as $tag){
			$this->LocTags[] = (string)$tag;
		}

		// Figure out authors and contributors
		foreach($xml->xpath('/package/metadata/dc:creator') ?: [] as $author){
			$id = '';

			if($author->attributes() !== null){
				$id = $author->attributes()->id;
			}

			$fileAs = null;
			$fileAsElement = $xml->xpath('/package/metadata/meta[@property="file-as"][@refines="#' . $id . '"]');
			if($fileAsElement !== false && sizeof($fileAsElement) > 0){
				$fileAs = (string)$fileAsElement[0];
			}

			$this->Authors[] = new Contributor(
								(string)$author,
								$fileAs,
								$this->NullIfEmpty($xml->xpath('/package/metadata/meta[@property="se:name.person.full-name"][@refines="#' . $id . '"]')),
								$this->NullIfEmpty($xml->xpath('/package/metadata/meta[@property="se:url.encyclopedia.wikipedia"][@refines="#' . $id . '"]')),
								'aut',
								$this->NullIfEmpty($xml->xpath('/package/metadata/meta[@property="se:url.authority.nacoaf"][@refines="#' . $id . '"]'))
							);
		}

		if(sizeof($this->Authors) == 0){
			throw new Exceptions\EbookParsingException('Invalid <dc:creator> element.');
		}

		$this->AuthorsUrl = preg_replace('|url:https://standardebooks.org/ebooks/([^/]+)/.*|ius', '/ebooks/\1', $this->Identifier);

		foreach($xml->xpath('/package/metadata/dc:contributor') ?: [] as $contributor){
			$id = '';
			if($contributor->attributes() !== null){
				$id = $contributor->attributes()->id;
			}

			foreach($xml->xpath('/package/metadata/meta[ (@property="role" or @property="se:role") and @refines="#' . $id . '"]') ?: [] as $role){
				$c = new Contributor(
							(string)$contributor,
							$this->NullIfEmpty($xml->xpath('/package/metadata/meta[@property="file-as"][@refines="#' . $id . '"]')),
							$this->NullIfEmpty($xml->xpath('/package/metadata/meta[@property="se:name.person.full-name"][@refines="#' . $id . '"]')),
							$this->NullIfEmpty($xml->xpath('/package/metadata/meta[@property="se:url.encyclopedia.wikipedia"][@refines="#' . $id . '"]')),
							$role,
							$this->NullIfEmpty($xml->xpath('/package/metadata/meta[@property="se:url.authority.nacoaf"][@refines="#' . $id . '"]'))
						);

				// A display-sequence of 0 indicates that we don't want to process this contributor
				$displaySequence = $this->NullIfEmpty($xml->xpath('/package/metadata/meta[@property="display-seq"][@refines="#' . $id . '"]'));
				if($displaySequence !== '0'){
					if($role == 'trl'){
						$this->Translators[] = $c;
					}

					if($role == 'ill'){
						$this->Illustrators[] = $c;
					}

					if($role == 'ctb'){
						$this->Contributors[] = $c;
					}
				}
			}

			// If we added an illustrator who is also the translator, remove the illustrator credit so the name doesn't appear twice
			foreach($this->Illustrators as $key => $illustrator){
				foreach($this->Translators as $translator){
					if($translator->Name == $illustrator->Name){
						unset($this->Illustrators[$key]);
						break;
					}
				}
			}

		}

		// Some basic data.
		$this->Description = $this->NullIfEmpty($xml->xpath('/package/metadata/dc:description'));
		$this->Language = $this->NullIfEmpty($xml->xpath('/package/metadata/dc:language'));
		$this->LongDescription = $this->NullIfEmpty($xml->xpath('/package/metadata/meta[@property="se:long-description"]'));

		$wordCount = 0;
		$wordCountElement = $xml->xpath('/package/metadata/meta[@property="se:word-count"]');
		if($wordCountElement !== false && sizeof($wordCountElement) > 0){
			$wordCount = (int)$wordCountElement[0];
		}
		$this->WordCount = $wordCount;

		$readingEase = 0;
		$readingEaseElement = $xml->xpath('/package/metadata/meta[@property="se:reading-ease.flesch"]');
		if($readingEaseElement !== false && sizeof($readingEaseElement) > 0){
			$readingEase = (float)$readingEaseElement[0];
		}
		$this->ReadingEase = $readingEase;

		if($this->ReadingEase !== null){
			if($this->ReadingEase >= 90){
				$this->ReadingEaseDescription = 'very easy';
			}

			if($this->ReadingEase >= 79 && $this->ReadingEase <= 89){
				$this->ReadingEaseDescription = 'easy';
			}

			if($this->ReadingEase > 69 && $this->ReadingEase <= 79){
				$this->ReadingEaseDescription = 'fairly easy';
			}

			if($this->ReadingEase > 59 && $this->ReadingEase <= 69){
				$this->ReadingEaseDescription = 'average difficulty';
			}

			if($this->ReadingEase > 49 && $this->ReadingEase <= 59){
				$this->ReadingEaseDescription = 'fairly difficult';
			}

			if($this->ReadingEase > 39 && $this->ReadingEase <= 49){
				$this->ReadingEaseDescription = 'difficult';
			}

			if($this->ReadingEase < 39){
				$this->ReadingEaseDescription = 'very difficult';
			}
		}

		// Figure out the reading time.
		$readingTime = ceil($this->WordCount / AVERAGE_READING_WORDS_PER_MINUTE);
		$this->ReadingTime = $readingTime;

		if($readingTime < 60){
			$this->ReadingTime .= ' minute';
			if($readingTime != 1){
				$this->ReadingTime .= 's';
			}
		}
		else{
			$readingTimeHours = floor($readingTime / 60);
			$readingTimeMinutes = ceil($readingTime % 60);
			$this->ReadingTime = $readingTimeHours . ' hour';
			if($readingTimeHours != 1){
				$this->ReadingTime .= 's';
			}

			if($readingTimeMinutes != 0){
				$this->ReadingTime .= ' ' . $readingTimeMinutes . ' minute';
				if($readingTimeMinutes != 1){
					$this->ReadingTime .= 's';
				}
			}
		}

		// Figure out ancillary links.

		// First the Wikipedia URLs.
		$this->WikipediaUrl = $this->NullIfEmpty($xml->xpath('/package/metadata/meta[@property="se:url.encyclopedia.wikipedia"][not(@refines)]'));

		// Next the page scan source URLs.
		foreach($xml->xpath('/package/metadata/dc:source') ?: [] as $element){
			$e = (string)$element;
			if(mb_stripos($e, 'gutenberg.org/') !== false){
				$this->Sources[] = new EbookSource(SOURCE_PROJECT_GUTENBERG, $e);
			}
			elseif(mb_stripos($e, 'gutenberg.net.au/') !== false){
				$this->Sources[] = new EbookSource(SOURCE_PROJECT_GUTENBERG_AUSTRALIA, $e);
			}
			elseif(mb_stripos($e, 'gutenberg.ca/') !== false){
				$this->Sources[] = new EbookSource(SOURCE_PROJECT_GUTENBERG_CANADA, $e);
			}
			elseif(mb_stripos($e, 'archive.org/') !== false){
				$this->Sources[] = new EbookSource(SOURCE_INTERNET_ARCHIVE, $e);
			}
			elseif(mb_stripos($e, 'hathitrust.org/') !== false){
				$this->Sources[] = new EbookSource(SOURCE_HATHI_TRUST, $e);
			}
			elseif(mb_stripos($e, 'wikisource.org/') !== false){
				$this->Sources[] = new EbookSource(SOURCE_WIKISOURCE, $e);
			}
			elseif(mb_stripos($e, 'books.google.com/') !== false || mb_stripos($e, 'google.com/books/') !== false){
				$this->Sources[] = new EbookSource(SOURCE_GOOGLE_BOOKS, $e);
			}
			elseif(mb_stripos($e, 'www.fadedpage.com') !== false){
				$this->Sources[] = new EbookSource(SOURCE_FADED_PAGE, $e);
			}
			else{
				$this->Sources[] = new EbookSource(SOURCE_OTHER, $e);
			}
		}

		// Next the GitHub URLs.
		$this->GitHubUrl = $this->NullIfEmpty($xml->xpath('/package/metadata/meta[@property="se:url.vcs.github"][not(@refines)]'));

		// Put together the full contributor string.
		$titleContributors = '';
		if(sizeof($this->Contributors) > 0){
			$titleContributors .= '. With ' . $this->GenerateContributorList($this->Contributors, false);
			$this->ContributorsHtml .= ' with ' . $this->GenerateContributorList($this->Contributors, false) . ';';
		}

		if(sizeof($this->Translators) > 0){
			$titleContributors .= '. Translated by ' . $this->GenerateContributorList($this->Translators, false);
			$this->ContributorsHtml .= ' translated by ' . $this->GenerateContributorList($this->Translators, false) . ';';
		}

		if(sizeof($this->Illustrators) > 0){
			$titleContributors .= '. Illustrated by ' . $this->GenerateContributorList($this->Illustrators, false);
			$this->ContributorsHtml .= ' illustrated by ' . $this->GenerateContributorList($this->Illustrators, false) . ';';
		}

		if($this->ContributorsHtml !== null){
			$this->ContributorsHtml = ucfirst(rtrim(trim($this->ContributorsHtml), ';'));

			if(substr(strip_tags($this->ContributorsHtml), -1) != '.'){
				$this->ContributorsHtml .= '.';
			}
		}

		$this->AuthorsHtml = $this->GenerateContributorList($this->Authors, true);

		// Now the complete title with credits.
		$this->TitleWithCreditsHtml = Formatter::ToPlainText($this->Title) . ', by ' . str_replace('&amp;', '&', $this->AuthorsHtml . $titleContributors);
	}

	public function GetCollectionPosition(Collection $collection): ?int{
		foreach($this->Collections as $c){
			if($c->Name == $collection->Name){
				return $c->SequenceNumber;
			}
		}

		return null;
	}

	public function Contains(string $query): bool{
		// When searching an ebook, we search the title, alternate title, author(s), SE tags, series data, and LoC tags.
		// Also, if the ebook is shorts or poetry, search the ToC as well.

		$searchString = $this->FullTitle ?? $this->Title;

		$searchString .= ' ' . $this->AlternateTitle;

		foreach($this->Collections as $collection){
			$searchString .= ' ' . $collection->Name;
		}

		foreach($this->Authors as $author){
			$searchString .= ' ' . $author->Name;
		}

		foreach($this->Tags as $tag){
			$searchString .= ' ' . $tag->Name;
		}

		foreach($this->LocTags as $tag){
			$searchString .= ' ' . $tag;
		}

		if($this->TocEntries !== null){
			foreach($this->TocEntries as $item){
				$searchString .= ' ' . $item;
			}
		}

		// Remove diacritics and non-alphanumeric characters
		$searchString = trim(preg_replace('|[^a-zA-Z0-9 ]|ius', ' ', Formatter::RemoveDiacritics($searchString)) ?? '');
		$query = trim(preg_replace('|[^a-zA-Z0-9 ]|ius', ' ', Formatter::RemoveDiacritics($query)) ?? '');

		if($query == ''){
			return false;
		}

		if(mb_stripos($searchString, $query) !== false){
			return true;
		}

		return false;
	}

	public function GenerateJsonLd(): string{
		$output = new stdClass();
		$output->{'@context'} = 'https://schema.org';
		$output->{'@type'} = 'Book';
		$output->bookFormat = 'EBook';

		$organizationObject = new stdClass();
		$organizationObject->{'@type'} = 'Organization';
		$organizationObject->name = 'Standard Ebooks';
		$organizationObject->logo = 'https://standardebooks.org/images/logo-full.svg';
		$organizationObject->url = 'https://standardebooks.org';
		$output->publisher = $organizationObject;

		$output->name = $this->Title;
		$output->image = SITE_URL . $this->DistCoverUrl;
		$output->thumbnailUrl = SITE_URL . $this->Url . '/downloads/cover-thumbnail.jpg';
		$output->url = SITE_URL . $this->Url;
		$output->{'@id'} = SITE_URL . $this->Url;
		$output->description = $this->Description;
		$output->inLanguage = $this->Language;

		if($this->WikipediaUrl){
			$output->sameAs = $this->WikipediaUrl;
		}

		$output->author = [];

		foreach($this->Authors as $contributor){
			$output->author[] = $this->GenerateContributorJsonLd($contributor);
		}

		$output->encoding = [];

		if($this->EpubUrl){
			$encodingObject = new stdClass();
			$encodingObject->{'@type'} = 'MediaObject';
			$encodingObject->encodingFormat = 'epub';
			$encodingObject->contentUrl = SITE_URL . $this->EpubUrl;
			$output->encoding[] = $encodingObject;
		}

		if($this->KepubUrl){
			$encodingObject = new stdClass();
			$encodingObject->{'@type'} = 'MediaObject';
			$encodingObject->encodingFormat = 'kepub';
			$encodingObject->contentUrl = SITE_URL . $this->KepubUrl;
			$output->encoding[] = $encodingObject;
		}

		if($this->AdvancedEpubUrl){
			$encodingObject = new stdClass();
			$encodingObject->{'@type'} = 'MediaObject';
			$encodingObject->encodingFormat = 'epub';
			$encodingObject->contentUrl = SITE_URL . $this->AdvancedEpubUrl;
			$output->encoding[] = $encodingObject;
		}

		if($this->Azw3Url){
			$encodingObject = new stdClass();
			$encodingObject->{'@type'} = 'MediaObject';
			$encodingObject->encodingFormat = 'azw3';
			$encodingObject->contentUrl = SITE_URL . $this->Azw3Url;
			$output->encoding[] = $encodingObject;
		}

		if(sizeof($this->Translators) > 0){
			$output->translator = [];
			foreach($this->Translators as $contributor){
				$output->translator[] = $this->GenerateContributorJsonLd($contributor);
			}
		}

		if(sizeof($this->Illustrators) > 0){
			$output->illustrator = [];
			foreach($this->Illustrators as $contributor){
				$output->illustrator[] = $this->GenerateContributorJsonLd($contributor);
			}
		}

		return json_encode($output, JSON_PRETTY_PRINT) ?: '';
	}

	private function GenerateContributorJsonLd(Contributor $contributor): stdClass{
		$object = new stdClass();
		$object->{'@type'} = 'Person';
		$object->name = $contributor->Name;

		if($contributor->WikipediaUrl){
			$object->sameAs = $contributor->WikipediaUrl;
		}

		if($contributor->FullName){
			$object->alternateName = $contributor->FullName;
		}

		return $object;
	}

	/**
	 * @param array<Contributor> $contributors
	 * @param bool $includeRdfa
	 */
	private function GenerateContributorList(array $contributors, bool $includeRdfa): string{
		$string = '';
		$i = 0;

		foreach($contributors as $contributor){
			$role = 'schema:contributor';
			switch($contributor->MarcRole){
				case 'trl':
					$role = 'schema:translator';
					break;
				case 'ill':
					$role = 'schema:illustrator';
					break;
			}

			if($contributor->WikipediaUrl){
				if($includeRdfa){
					$string .= '<a property="' . $role . '" typeof="schema:Person" href="' . Formatter::ToPlainText($contributor->WikipediaUrl) .'"><span property="schema:name">' . Formatter::ToPlainText($contributor->Name) . '</span>';

					if($contributor->NacoafUrl){
						$string .= '<meta property="schema:sameAs" content="' . Formatter::ToPlainText($contributor->NacoafUrl) . '"/>';
					}
				}
				else{
					$string .= '<a href="' . Formatter::ToPlainText($contributor->WikipediaUrl) .'">' . Formatter::ToPlainText($contributor->Name);
				}

				$string .= '</a>';
			}
			else{
				if($includeRdfa){
					$string .= '<span property="' . $role . '" typeof="schema:Person"><span property="schema:name">' . Formatter::ToPlainText($contributor->Name) . '</span>';

					if($contributor->NacoafUrl){
						$string .= '<meta property="schema:sameAs" content="' . Formatter::ToPlainText($contributor->NacoafUrl) . '"/>';
					}

					$string .= '</span>';
				}
				else{
					$string .= Formatter::ToPlainText($contributor->Name);
				}
			}

			if($i == sizeof($contributors) - 2 && sizeof($contributors) > 2){
				$string .= ', and ';
			}
			elseif($i == sizeof($contributors) - 2){
				$string .= ' and ';
			}
			elseif($i != sizeof($contributors) - 1){
				$string .= ', ';
			}

			$i++;
		}

		return $string;
	}

	public function GenerateContributorsRdfa(): string{
		$string = '';
		$i = 0;

		foreach($this->Translators as $contributor){
			$role = 'schema:contributor';
			switch($contributor->MarcRole){
				case 'trl':
					$role = 'schema:translator';
					break;
				case 'ill':
					$role = 'schema:illustrator';
					break;
			}

			if($contributor->WikipediaUrl){
				$string .= '<div property="' . $role . '" typeof="schema:Person" resource="/contributors/' . Formatter::MakeUrlSafe($contributor->Name) .'">' . "\n";
			}
			else{
				$string .= '<div property="' . $role . '" typeof="schema:Person">' . "\n";
			}

			$string .= '<meta property="schema:name" content="' . Formatter::ToPlainText($contributor->Name) . '"/>' . "\n";

			if($contributor->WikipediaUrl){
				$string .= '<meta property="schema:sameAs" content="' . Formatter::ToPlainText($contributor->WikipediaUrl) . '"/>' . "\n";
			}

			if($contributor->NacoafUrl){
				$string .= '<meta property="schema:sameAs" content="' . Formatter::ToPlainText($contributor->NacoafUrl) . '"/>' . "\n";
			}

			$string .= '</div>';

			$i++;
		}

		return $string;
	}

	private function NullIfEmpty($elements): ?string{
		if($elements === false){
			return null;
		}

		// Helper function when getting values from SimpleXml.
		// Checks if the result is set, and returns the value if so; if the value is the empty string, return null.
		if(isset($elements[0])){
			$str = (string)$elements[0];
			if($str !== ''){
				return $str;
			}
		}

		return null;
	}

	public function HasTag(string $tag): bool{
		foreach($this->Tags as $t){
			if(strtolower($t->Name) == strtolower($tag)){
				return true;
			}
		}

		return false;
	}

	public function IsInCollection(string $collection): bool{
		foreach($this->Collections as $c){
			if(strtolower(Formatter::RemoveDiacritics($c->Name)) == strtolower(Formatter::RemoveDiacritics($collection))){
				return true;
			}
		}

		return false;
	}
}
