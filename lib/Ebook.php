<?

use Safe\DateTimeImmutable;

use function Safe\file_get_contents;
use function Safe\filesize;
use function Safe\json_encode;
use function Safe\glob;
use function Safe\preg_match;
use function Safe\preg_replace;
use function Safe\sprintf;
use function Safe\shell_exec;

/**
 * @property array<GitCommit> $GitCommits
 * @property array<EbookTag> $EbookTags
 * @property array<LocSubject> $LocSubjects
 * @property array<Collection> $Collections
 * @property array<EbookSource> $Sources
 * @property array<Contributor> $Authors
 * @property array<Contributor> $Illustrators
 * @property array<Contributor> $Translators
 * @property array<Contributor> $Contributors
 * @property ?array<string> $TocEntries
 * @property string $IndexableText
 */
class Ebook{
	use Traits\Accessor;

	public ?int $EbookId = null;
	public string $WwwFilesystemPath;
	public string $RepoFilesystemPath;
	public string $Url;
	public ?string $KindleCoverUrl;
	public string $EpubUrl;
	public string $AdvancedEpubUrl;
	public string $KepubUrl;
	public string $Azw3Url;
	public bool $HasDownloads;
	/** @var array<GitCommit> $GitCommits */
	public $GitCommits = [];
	/** @var array<EbookTag> $Tags */
	public $Tags = [];
	/** @var array<LocSubject> $LocSubjects */
	public $LocSubjects = [];
	/** @var array<Collection> $Collections */
	public $Collections = [];
	public string $Identifier;
	public string $UrlSafeIdentifier;
	public string $HeroImageUrl;
	public string $HeroImageAvifUrl;
	public string $HeroImage2xUrl;
	public string $HeroImage2xAvifUrl;
	public string $CoverImageUrl;
	public string $CoverImageAvifUrl;
	public string $CoverImage2xUrl;
	public string $CoverImage2xAvifUrl;
	public string $DistCoverUrl;
	public ?string $Title = null;
	public ?string $FullTitle = null;
	public ?string $AlternateTitle = null;
	public ?string $Description = null;
	public ?string $LongDescription = null;
	public ?string $Language = null;
	public int $WordCount;
	public float $ReadingEase;
	public string $ReadingEaseDescription;
	public string $ReadingTime;
	public ?string $GitHubUrl = null;
	public ?string $WikipediaUrl = null;
	/** @var array<EbookSource> $Sources */
	public $Sources = [];
	/** @var array<Contributor> $Authors */
	public $Authors = [];
	public string $AuthorsHtml;
	public string $AuthorsUrl; // This is a single URL even if there are multiple authors; for example, /ebooks/karl-marx_friedrich-engels/
	/** @var array<Contributor> $Illustrators */
	public $Illustrators = [];
	/** @var array<Contributor> $Translators */
	public $Translators = [];
	/** @var array<Contributor> $Contributors */
	public $Contributors = [];
	public ?string $ContributorsHtml = null;
	public string $TitleWithCreditsHtml = '';
	public DateTimeImmutable $Created;
	public DateTimeImmutable $Updated;
	public ?string $TextUrl;
	public ?string $TextSinglePageUrl;
	public ?string $TextSinglePageSizeNumber = null;
	public ?string $TextSinglePageSizeUnit = null;
	public ?int $TextSinglePageByteCount = null;
	/** @var ?array<string> $TocEntries */
	public $TocEntries = null; // A list of non-Roman ToC entries ONLY IF the work has the 'se:is-a-collection' metadata element, null otherwise
	protected ?string $_IndexableText = null;

	// *******
	// GETTERS
	// *******

	protected function GetIndexableText(): string{
		if($this->_IndexableText === null){
			$this->_IndexableText = $this->FullTitle ?? $this->Title;

			$this->_IndexableText .= ' ' . $this->AlternateTitle;

			foreach($this->Collections as $collection){
				$this->_IndexableText .= ' ' . $collection->Name;
			}

			foreach($this->Authors as $author){
				$this->_IndexableText .= ' ' . $author->Name;
			}

			foreach($this->Tags as $tag){
				$this->_IndexableText .= ' ' . $tag->Name;
			}

			foreach($this->LocSubjects as $subject){
				$this->_IndexableText .= ' ' . $subject->Name;
			}

			if($this->TocEntries !== null){
				foreach($this->TocEntries as $item){
					$this->_IndexableText .= ' ' . $item;
				}
			}

			$this->_IndexableText .= ' ' . $this->Description;
			$this->_IndexableText .= ' ' . $this->LongDescription;
		}

		return $this->_IndexableText;
	}

	/**
	 * @throws Exceptions\EbookNotFoundException
	 * @throws Exceptions\EbookParsingException
	 * @throws Exceptions\InvalidGitCommitException
	 */
	public function __construct(?string $wwwFilesystemPath = null){
		if($wwwFilesystemPath === null){
			return;
		}

		// First, construct a source repo path from our WWW filesystem path.
		$this->RepoFilesystemPath = str_replace(EBOOKS_DIST_PATH, '', $wwwFilesystemPath);
		$this->RepoFilesystemPath = SITE_ROOT . '/ebooks/' . str_replace('/', '_', $this->RepoFilesystemPath) . '.git';

		if(!is_dir($this->RepoFilesystemPath)){ // On dev systems we might not have the bare repos, so make an adjustment
			try{
				$this->RepoFilesystemPath = preg_replace('/\.git$/ius', '', $this->RepoFilesystemPath);
			}
			catch(\Exception){
				// We may get an exception from preg_replace if the passed repo wwwFilesystemPath contains invalid UTF-8 characters, whichis  a common injection attack vector
				throw new Exceptions\EbookNotFoundException('Invalid repo filesystem path: ' . $this->RepoFilesystemPath);
			}
		}

		if(!is_dir($wwwFilesystemPath)){
			throw new Exceptions\EbookNotFoundException('Invalid www filesystem path: ' . $wwwFilesystemPath);
		}

		if(!is_dir($this->RepoFilesystemPath)){
			throw new Exceptions\EbookNotFoundException('Invalid repo filesystem path: ' . $this->RepoFilesystemPath);
		}

		if(!is_file($wwwFilesystemPath . '/content.opf')){
			throw new Exceptions\EbookNotFoundException('Invalid content.opf file: ' . $wwwFilesystemPath . '/content.opf');
		}

		$this->WwwFilesystemPath = $wwwFilesystemPath;
		$this->Url = str_replace(WEB_ROOT, '', $this->WwwFilesystemPath);

		$rawMetadata = file_get_contents($wwwFilesystemPath . '/content.opf');

		// Get the SE identifier.
		preg_match('|<dc:identifier[^>]*?>(.+?)</dc:identifier>|ius', $rawMetadata, $matches);
		if(sizeof($matches) != 2){
			throw new Exceptions\EbookParsingException('Invalid <dc:identifier> element.');
		}
		$this->Identifier = (string)$matches[1];

		$this->UrlSafeIdentifier = str_replace(['url:https://standardebooks.org/ebooks/', '/'], ['', '_'], $this->Identifier);

		$this->TextUrl = $this->Url . '/text';

		try{
			// PHP Safe throws an exception from filesize() if the file doesn't exist, but PHP still
			// emits a warning. So, just silence the warning.
			$this->TextSinglePageByteCount = @filesize($this->WwwFilesystemPath . '/text/single-page.xhtml');
			$sizes = 'BKMGTP';
			$factor = intval(floor((strlen((string)$this->TextSinglePageByteCount) - 1) / 3));
			try{
				$this->TextSinglePageSizeNumber = sprintf('%.1f', $this->TextSinglePageByteCount / pow(1024, $factor));
			}
			catch(\DivisionByZeroError){
				$this->TextSinglePageSizeNumber = '0';
			}
			$this->TextSinglePageSizeUnit = $sizes[$factor] ?? '';
			$this->TextSinglePageUrl = $this->Url . '/text/single-page';
		}
		catch(\Exception){
			// Single page file doesn't exist, just pass
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
		$historyEntries = explode("\n",  shell_exec('cd ' . escapeshellarg($this->RepoFilesystemPath) . ' && git log -n5 --pretty=format:"%ct %H %s"'));

		foreach($historyEntries as $entry){
			$array = explode(' ', $entry, 3);
			$this->GitCommits[] = new GitCommit($array[0], $array[1], $array[2]);
		}

		// Get cover image URLs.
		$gitFolderPath = $this->RepoFilesystemPath;
		if(stripos($this->RepoFilesystemPath, '.git') === false){
			$gitFolderPath = $gitFolderPath . '/.git';
		}
		$hash = substr(sha1($this->GitCommits[0]->Created->format('U') . ' ' . $this->GitCommits[0]->Message), 0, 8);
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
		try{
			$xml = new SimpleXMLElement(str_replace('xmlns=', 'ns=', $rawMetadata));
		}
		catch(\Exception $ex){
			throw new Exceptions\EbookParsingException($ex->getMessage());
		}

		$xml->registerXPathNamespace('dc', 'http://purl.org/dc/elements/1.1/');

		$this->Title = $this->NullIfEmpty($xml->xpath('/package/metadata/dc:title'));
		if($this->Title === null){
			throw new Exceptions\EbookParsingException('Invalid <dc:title> element.');
		}

		$this->Title = str_replace('\'', '’', $this->Title);

		$this->FullTitle = $this->NullIfEmpty($xml->xpath('/package/metadata/dc:title[@id="fulltitle"]'));

		$this->AlternateTitle = $this->NullIfEmpty($xml->xpath('/package/metadata/meta[@property="dcterms:alternate"][@refines="#title"]'));

		$date = $xml->xpath('/package/metadata/dc:date') ?: [];
		if($date !== false && sizeof($date) > 0){
			/** @throws void */
			$this->Created = new DateTimeImmutable((string)$date[0]);
		}

		$modifiedDate = $xml->xpath('/package/metadata/meta[@property="dcterms:modified"]') ?: [];
		if($modifiedDate !== false && sizeof($modifiedDate) > 0){
			/** @throws void */
			$this->Updated = new DateTimeImmutable((string)$modifiedDate[0]);
		}

		// Get SE tags
		foreach($xml->xpath('/package/metadata/meta[@property="se:subject"]') ?: [] as $tag){
			$ebookTag = new EbookTag();
			$ebookTag->Name = $tag;
			$this->Tags[] = $ebookTag;
		}

		$includeToc = sizeof($xml->xpath('/package/metadata/meta[@property="se:is-a-collection"]') ?: []) > 0;

		// Fill the ToC if necessary
		if($includeToc){
			$this->TocEntries = [];
			try{
				$tocDom = new SimpleXMLElement(str_replace('xmlns=', 'ns=', file_get_contents($wwwFilesystemPath . '/toc.xhtml')));
			}
			catch(\Exception $ex){
				throw new Exceptions\EbookParsingException($ex->getMessage());
			}
			$tocDom->registerXPathNamespace('epub', 'http://www.idpf.org/2007/ops');
			foreach($tocDom->xpath('/html/body//nav[@epub:type="toc"]//a[not(contains(@epub:type, "z3998:roman")) and not(text() = "Titlepage" or text() = "Imprint" or text() = "Colophon" or text() = "Endnotes" or text() = "Uncopyright") and not(contains(@href, "halftitle"))]') ?: [] as $item){
				$this->TocEntries[] = (string)$item;
			}
		}

		// Get SE collections
		foreach($xml->xpath('/package/metadata/meta[@property="belongs-to-collection"]') ?: [] as $collection){
			$c = new Collection($collection);
			$id = $collection->attributes()->id ?? '';

			foreach($xml->xpath('/package/metadata/meta[@refines="#' . $id . '"][@property="group-position"]') ?: [] as $s){
				$c->SequenceNumber = (int)$s;
			}
			foreach($xml->xpath('/package/metadata/meta[@refines="#' . $id . '"][@property="collection-type"]') ?: [] as $s){
				$c->Type = (string)$s;
			}
			$this->Collections[] = $c;
		}

		// Get LoC tags
		foreach($xml->xpath('/package/metadata/dc:subject') ?: [] as $subject){
			$locSubject = new LocSubject();
			$locSubject->Name = $subject;
			$this->LocSubjects[] = $locSubject;
		}

		// Figure out authors and contributors
		foreach($xml->xpath('/package/metadata/dc:creator') ?: [] as $author){
			$id = '';

			if($author->attributes() !== null){
				$id = $author->attributes()->id;
			}

			$fileAs = null;
			$fileAsElement = $xml->xpath('/package/metadata/meta[@property="file-as"][@refines="#' . $id . '"]') ?: [];
			if($fileAsElement !== false && sizeof($fileAsElement) > 0){
				$fileAs = (string)$fileAsElement[0];
			}
			else{
				$fileAs = (string)$author;
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
			if($this->ReadingEase > 89){
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

			if($this->ReadingEase <= 39){
				$this->ReadingEaseDescription = 'very difficult';
			}
		}

		// Figure out the reading time.
		$readingTime = ceil($this->WordCount / AVERAGE_READING_WORDS_PER_MINUTE);
		$this->ReadingTime = (string)$readingTime;

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
				$this->Sources[] = new EbookSource(EbookSourceType::ProjectGutenberg, $e);
			}
			elseif(mb_stripos($e, 'gutenberg.net.au/') !== false){
				$this->Sources[] = new EbookSource(EbookSourceType::ProjectGutenbergAustralia, $e);
			}
			elseif(mb_stripos($e, 'gutenberg.ca/') !== false){
				$this->Sources[] = new EbookSource(EbookSourceType::ProjectGutenbergCanada, $e);
			}
			elseif(mb_stripos($e, 'archive.org/details') !== false){
				// `/details` excludes Wayback Machine URLs which may sometimes occur, for example in Lyrical Ballads
				$this->Sources[] = new EbookSource(EbookSourceType::InternetArchive, $e);
			}
			elseif(mb_stripos($e, 'hathitrust.org/') !== false){
				$this->Sources[] = new EbookSource(EbookSourceType::HathiTrust, $e);
			}
			elseif(mb_stripos($e, 'wikisource.org/') !== false){
				$this->Sources[] = new EbookSource(EbookSourceType::Wikisource, $e);
			}
			elseif(mb_stripos($e, 'books.google.com/') !== false || mb_stripos($e, 'google.com/books/') !== false){
				$this->Sources[] = new EbookSource(EbookSourceType::GoogleBooks, $e);
			}
			elseif(mb_stripos($e, 'www.fadedpage.com') !== false){
				$this->Sources[] = new EbookSource(EbookSourceType::FadedPage, $e);
			}
			else{
				$this->Sources[] = new EbookSource(EbookSourceType::Other, $e);
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
		$this->TitleWithCreditsHtml = Formatter::EscapeHtml($this->Title) . ', by ' . str_replace('&amp;', '&', $this->AuthorsHtml . $titleContributors);
	}


	// *******
	// METHODS
	// *******

	public function Validate(): void{
		$error = new Exceptions\ValidationException();

		if($this->Identifier === null || $this->Identifier == ''){
			$error->Add(new Exceptions\EbookIdentifierRequiredException());
		}

		if($this->Identifier !== null && strlen($this->Identifier) > EBOOKS_MAX_STRING_LENGTH){
			$error->Add(new Exceptions\StringTooLongException('Ebook Identifier'));
		}

		if($this->Title === null || $this->Title == ''){
			$error->Add(new Exceptions\EbookTitleRequiredException());
		}

		if($this->Title !== null && strlen($this->Title) > EBOOKS_MAX_STRING_LENGTH){
			$error->Add(new Exceptions\StringTooLongException('Ebook Title'));
		}

		// TODO: Add more validation.

		if($error->HasExceptions){
			throw $error;
		}
	}

	public function CreateOrUpdate(): void{
		$existingEbook = Library::GetEbookByIdentifier($this->Identifier);

		if($existingEbook === null){
			$this->Create();
			return;
		}

		$this->EbookId = $existingEbook->EbookId;
		$this->Save();
	}

	private function InsertTagStrings(): void{
		$tags = [];
		foreach($this->Tags as $ebookTag){
			$tags[] = EbookTag::GetOrCreate($ebookTag);
		}
		$this->Tags = $tags;
	}

	private function InsertLocSubjectStrings(): void{
		$subjects = [];
		foreach($this->LocSubjects as $locSubject){
			$subjects[] = LocSubject::GetOrCreate($locSubject);
		}
		$this->LocSubjects = $subjects;
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

		foreach($this->LocSubjects as $subject){
			$searchString .= ' ' . $subject->Name;
		}

		if($this->TocEntries !== null){
			foreach($this->TocEntries as $item){
				$searchString .= ' ' . $item;
			}
		}

		// Remove diacritics and non-alphanumeric characters
		$searchString = trim(preg_replace('|[^a-zA-Z0-9 ]|ius', ' ', Formatter::RemoveDiacritics($searchString)));
		$query = trim(preg_replace('|[^a-zA-Z0-9 ]|ius', ' ', Formatter::RemoveDiacritics($query)));

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

		return json_encode($output, JSON_PRETTY_PRINT);
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
					$string .= '<a property="' . $role . '" typeof="schema:Person" href="' . Formatter::EscapeHtml($contributor->WikipediaUrl) .'"><span property="schema:name">' . Formatter::EscapeHtml($contributor->Name) . '</span>';

					if($contributor->NacoafUrl){
						$string .= '<meta property="schema:sameAs" content="' . Formatter::EscapeHtml($contributor->NacoafUrl) . '"/>';
					}
				}
				else{
					$string .= '<a href="' . Formatter::EscapeHtml($contributor->WikipediaUrl) .'">' . Formatter::EscapeHtml($contributor->Name);
				}

				$string .= '</a>';
			}
			else{
				if($includeRdfa){
					$string .= '<span property="' . $role . '" typeof="schema:Person"><span property="schema:name">' . Formatter::EscapeHtml($contributor->Name) . '</span>';

					if($contributor->NacoafUrl){
						$string .= '<meta property="schema:sameAs" content="' . Formatter::EscapeHtml($contributor->NacoafUrl) . '"/>';
					}

					$string .= '</span>';
				}
				else{
					$string .= Formatter::EscapeHtml($contributor->Name);
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

			$string .= '<meta property="schema:name" content="' . Formatter::EscapeHtml($contributor->Name) . '"/>' . "\n";

			if($contributor->WikipediaUrl){
				$string .= '<meta property="schema:sameAs" content="' . Formatter::EscapeHtml($contributor->WikipediaUrl) . '"/>' . "\n";
			}

			if($contributor->NacoafUrl){
				$string .= '<meta property="schema:sameAs" content="' . Formatter::EscapeHtml($contributor->NacoafUrl) . '"/>' . "\n";
			}

			$string .= '</div>';

			$i++;
		}

		return $string;
	}


	/**
	 * @param array<SimpleXMLElement>|false|null $elements
	 */
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
			if(strtolower($t->UrlName) == strtolower($tag)){
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

	// ***********
	// ORM METHODS
	// ***********

	public function Create(): void{
		$this->Validate();

		$this->InsertTagStrings();
		$this->InsertLocSubjectStrings();

		Db::Query('
			INSERT into Ebooks (Identifier, WwwFilesystemPath, RepoFilesystemPath, KindleCoverUrl, EpubUrl,
				AdvancedEpubUrl, KepubUrl, Azw3Url, DistCoverUrl, Title, FullTitle, AlternateTitle,
				Description, LongDescription, Language, WordCount, ReadingEase, GitHubUrl, WikipediaUrl,
				EbookCreated, EbookUpdated, TextSinglePageByteCount, IndexableText)
			values (?,
				?,
				?,
				?,
				?,
				?,
				?,
				?,
				?,
				?,
				?,
				?,
				?,
				?,
				?,
				?,
				?,
				?,
				?,
				?,
				?,
				?,
				?)
		', [$this->Identifier, $this->WwwFilesystemPath, $this->RepoFilesystemPath, $this->KindleCoverUrl, $this->EpubUrl,
				$this->AdvancedEpubUrl, $this->KepubUrl, $this->Azw3Url, $this->DistCoverUrl, $this->Title,
				$this->FullTitle, $this->AlternateTitle, $this->Description, $this->LongDescription,
				$this->Language, $this->WordCount, $this->ReadingEase, $this->GitHubUrl, $this->WikipediaUrl,
				$this->Created, $this->Updated, $this->TextSinglePageByteCount, $this->IndexableText]);

		$this->EbookId = Db::GetLastInsertedId();

		$this->InsertTags();
		$this->InsertLocSubjects();
		$this->InsertGitCommits();
		$this->InsertCollections();
		$this->InsertSources();
		$this->InsertContributors();
		$this->InsertTocEntries();
	}

	public function Save(): void{
		$this->Validate();

		$this->InsertTagStrings();
		$this->InsertLocSubjectStrings();

		Db::Query('
			UPDATE Ebooks
			set
			Identifier = ?,
			WwwFilesystemPath = ?,
			RepoFilesystemPath = ?,
			KindleCoverUrl = ?,
			EpubUrl = ?,
			AdvancedEpubUrl = ?,
			KepubUrl = ?,
			Azw3Url = ?,
			DistCoverUrl = ?,
			Title = ?,
			FullTitle = ?,
			AlternateTitle = ?,
			Description = ?,
			LongDescription = ?,
			Language = ?,
			WordCount = ?,
			ReadingEase = ?,
			GitHubUrl = ?,
			WikipediaUrl = ?,
			EbookCreated = ?,
			EbookUpdated = ?,
			TextSinglePageByteCount = ?,
			IndexableText = ?
			where
			EbookId = ?
		', [$this->Identifier, $this->WwwFilesystemPath, $this->RepoFilesystemPath, $this->KindleCoverUrl, $this->EpubUrl,
				$this->AdvancedEpubUrl, $this->KepubUrl, $this->Azw3Url, $this->DistCoverUrl, $this->Title,
				$this->FullTitle, $this->AlternateTitle, $this->Description, $this->LongDescription,
				$this->Language, $this->WordCount, $this->ReadingEase, $this->GitHubUrl, $this->WikipediaUrl,
				$this->Created, $this->Updated, $this->TextSinglePageByteCount, $this->IndexableText,
				$this->EbookId]);

		$this->DeleteTags();
		$this->InsertTags();

		$this->DeleteLocSubjects();
		$this->InsertLocSubjects();

		$this->DeleteGitCommits();
		$this->InsertGitCommits();

		$this->DeleteCollections();
		$this->InsertCollections();

		$this->DeleteSources();
		$this->InsertSources();

		$this->DeleteContributors();
		$this->InsertContributors();

		$this->DeleteTocEntries();
		$this->InsertTocEntries();
	}

	private function DeleteTags(): void{
		Db::Query('
			DELETE from EbookTags
			where
			EbookId = ?
		', [$this->EbookId]
		);
	}

	private function InsertTags(): void{
		foreach($this->Tags as $tag){
			Db::Query('
				INSERT into EbookTags (EbookId, TagId)
				values (?,
				        ?)
			', [$this->EbookId, $tag->TagId]);
		}
	}

	private function DeleteLocSubjects(): void{
		Db::Query('
			DELETE from EbookLocSubjects
			where
			EbookId = ?
		', [$this->EbookId]
		);
	}

	private function InsertLocSubjects(): void{
		foreach($this->LocSubjects as $locSubject){
			Db::Query('
				INSERT into EbookLocSubjects (EbookId, LocSubjectId)
				values (?,
				        ?)
			', [$this->EbookId, $locSubject->LocSubjectId]);
		}
	}

	private function DeleteGitCommits(): void{
		Db::Query('
			DELETE from GitCommits
			where
			EbookId = ?
		', [$this->EbookId]
		);
	}

	private function InsertGitCommits(): void{
		foreach($this->GitCommits as $commit){
			Db::Query('
				INSERT into GitCommits (EbookId, Created, Message, Hash)
				values (?,
					?,
					?,
				        ?)
			', [$this->EbookId, $commit->Created, $commit->Message, $commit->Hash]);
		}
	}

	private function DeleteCollections(): void{
		Db::Query('
			DELETE from Collections
			where
			EbookId = ?
		', [$this->EbookId]
		);
	}

	private function InsertCollections(): void{
		foreach($this->Collections as $collection){
			Db::Query('
				INSERT into Collections (EbookId, Name, UrlName, SequenceNumber, Type)
				values (?,
					?,
					?,
					?,
				        ?)
			', [$this->EbookId, $collection->Name, $collection->UrlName, $collection->SequenceNumber, $collection->Type]);
		}
	}

	private function DeleteSources(): void{
		Db::Query('
			DELETE from EbookSources
			where
			EbookId = ?
		', [$this->EbookId]
		);
	}

	private function InsertSources(): void{
		foreach($this->Sources as $source){
			Db::Query('
				INSERT into EbookSources (EbookId, Type, Url)
				values (?,
					?,
				        ?)
			', [$this->EbookId, $source->Type->value, $source->Url]);
		}
	}

	private function DeleteContributors(): void{
		Db::Query('
			DELETE from Contributors
			where
			EbookId = ?
		', [$this->EbookId]
		);
	}

	private function InsertContributors(): void{
		$allContributors = array_merge($this->Authors, $this->Illustrators, $this->Translators, $this->Contributors);
		foreach($allContributors as $sortOrder => $contributor){
			Db::Query('
				INSERT into Contributors (EbookId, Name, UrlName, SortName, WikipediaUrl, MarcRole, FullName,
					NacoafUrl, SortOrder)
				values (?,
					?,
					?,
					?,
					?,
					?,
					?,
					?,
				        ?)
			', [$this->EbookId, $contributor->Name, $contributor->UrlName, $contributor->SortName,
				$contributor->WikipediaUrl, $contributor->MarcRole, $contributor->FullName,
				$contributor->NacoafUrl, $sortOrder]);
		}
	}

	private function DeleteTocEntries(): void{
		Db::Query('
			DELETE from TocEntries
			where
			EbookId = ?
		', [$this->EbookId]
		);
	}

	private function InsertTocEntries(): void{
		if($this->TocEntries !== null){
			foreach($this->TocEntries as $tocEntry){
				Db::Query('
					INSERT into TocEntries (EbookId, TocEntry)
					values (?,
						?)
				', [$this->EbookId, $tocEntry]);
			}
		}
	}
}
