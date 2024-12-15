<?
use Safe\DateTimeImmutable;

use function Safe\file_get_contents;
use function Safe\filesize;
use function Safe\json_encode;
use function Safe\glob;
use function Safe\preg_match;
use function Safe\preg_replace;
use function Safe\shell_exec;

/**
 * @property array<GitCommit> $GitCommits
 * @property array<EbookTag> $Tags
 * @property array<LocSubject> $LocSubjects
 * @property array<CollectionMembership> $CollectionMemberships
 * @property array<EbookSource> $Sources
 * @property array<Contributor> $Authors
 * @property array<Contributor> $Illustrators
 * @property array<Contributor> $Translators
 * @property array<Contributor> $Contributors
 * @property ?array<string> $TocEntries A list of non-Roman ToC entries *only if* the work has the `se:is-a-collection` metadata element; `null` otherwise.
 * @property string $Url
 * @property bool $HasDownloads
 * @property string $UrlSafeIdentifier
 * @property string $HeroImageUrl
 * @property string $HeroImageAvifUrl
 * @property string $HeroImage2xUrl
 * @property string $HeroImage2xAvifUrl
 * @property string $CoverImageUrl
 * @property string $CoverImageAvifUrl
 * @property string $CoverImage2xUrl
 * @property string $CoverImage2xAvifUrl
 * @property string $ReadingEaseDescription
 * @property string $ReadingTime
 * @property string $AuthorsHtml
 * @property string $AuthorsUrl This is a single URL even if there are multiple authors; for example, `/ebooks/karl-marx_friedrich-engels/`.
 * @property string $ContributorsHtml
 * @property string $TitleWithCreditsHtml
 * @property string $TextUrl
 * @property string $TextSinglePageUrl
 * @property string $TextSinglePageSizeFormatted
 * @property string $IndexableText
 * @property ?EbookPlaceholder $EbookPlaceholder
 * @property array<Project> $Projects
 * @property ?Project $ProjectInProgress
 */
class Ebook{
	use Traits\Accessor;

	public int $EbookId;
	public string $Identifier;
	public ?string $WwwFilesystemPath = null;
	public ?string $RepoFilesystemPath = null;
	public ?string $KindleCoverUrl = null;
	public ?string $EpubUrl = null;
	public ?string $AdvancedEpubUrl = null;
	public ?string $KepubUrl = null;
	public ?string $Azw3Url = null;
	public ?string $DistCoverUrl = null;
	public string $Title;
	public ?string $FullTitle = null;
	public ?string $AlternateTitle = null;
	public ?string $Description = null;
	public ?string $LongDescription = null;
	public ?string $Language = null;
	public ?int $WordCount = null;
	public ?float $ReadingEase = null;
	public ?string $GitHubUrl = null;
	public ?string $WikipediaUrl = null;
	/** When the ebook was published. */
	public ?DateTimeImmutable $EbookCreated = null;
	/** When the ebook was updated. */
	public ?DateTimeImmutable $EbookUpdated = null;
	/** When the database row was created. */
	public DateTimeImmutable $Created;
	/** When the database row was updated. */
	public DateTimeImmutable $Updated;
	public ?int $TextSinglePageByteCount = null;

	/** @var array<GitCommit> $_GitCommits */
	protected array $_GitCommits;
	/** @var array<EbookTag> $_Tags */
	protected array $_Tags;
	/** @var array<LocSubject> $_LocSubjects */
	protected array $_LocSubjects;
	/** @var array<CollectionMembership> $_CollectionMemberships */
	protected array $_CollectionMemberships;
	/** @var array<EbookSource> $_Sources */
	protected array $_Sources;
	/** @var array<Contributor> $_Authors */
	protected array $_Authors;
	/** @var array<Contributor> $_Illustrators */
	protected array $_Illustrators;
	/** @var array<Contributor> $_Translators */
	protected array$_Translators;
	/** @var array<Contributor> $_Contributors */
	protected array $_Contributors;
	/** @var ?array<string> $_TocEntries */
	protected ?array $_TocEntries = null;
	protected string $_Url;
	protected bool $_HasDownloads;
	protected string $_UrlSafeIdentifier;
	protected string $_HeroImageUrl;
	protected string $_HeroImageAvifUrl;
	protected string $_HeroImage2xUrl;
	protected string $_HeroImage2xAvifUrl;
	protected string $_CoverImageUrl;
	protected string $_CoverImageAvifUrl;
	protected string $_CoverImage2xUrl;
	protected string $_CoverImage2xAvifUrl;
	protected string $_ReadingEaseDescription;
	protected string $_ReadingTime;
	protected string $_AuthorsHtml;
	protected string $_AuthorsUrl;
	protected string $_ContributorsHtml;
	protected string $_TitleWithCreditsHtml;
	protected string $_TextUrl;
	protected string $_TextSinglePageUrl;
	protected string $_TextSinglePageSizeFormatted;
	protected string $_IndexableText;
	protected ?EbookPlaceholder $_EbookPlaceholder = null;
	/** @var array<Project> $_Projects */
	protected array $_Projects;
	protected ?Project $_ProjectInProgress;

	// *******
	// GETTERS
	// *******

	/**
	 * @return array<Project>
	 */
	protected function GetProjects(): array{
		if(!isset($this->_Projects)){
			$this->_Projects = Db::Query('
							SELECT *
							from Projects
							where EbookId = ?
							order by Created desc
						', [$this->EbookId], Project::class);
		}

		return $this->_Projects;
	}

	protected function GetProjectInProgress(): ?Project{
		if(!isset($this->_ProjectInProgress)){
			if(!isset($this->EbookId)){
				$this->_ProjectInProgress = null;
			}
			else{
				$this->_ProjectInProgress = Db::Query('
								SELECT *
								from Projects
								where EbookId = ?
								and Status in (?, ?)
							', [$this->EbookId, Enums\ProjectStatusType::InProgress, Enums\ProjectStatusType::Stalled], Project::class)[0] ?? null;
			}
		}

		return $this->_ProjectInProgress;
	}

	/**
	 * @return array<GitCommit>
	 */
	protected function GetGitCommits(): array{
		if(!isset($this->_GitCommits)){
			$this->_GitCommits = Db::Query('
							SELECT *
							from GitCommits
							where EbookId = ?
							order by Created desc
						', [$this->EbookId], GitCommit::class);
		}

		return $this->_GitCommits;
	}

	/**
	 * @return array<EbookTag>
	 */
	protected function GetTags(): array{
		if(!isset($this->_Tags)){
			$this->_Tags = Db::Query('
						SELECT t.*
						from Tags t
						inner join EbookTags et using (TagId)
						where EbookId = ?
						order by SortOrder asc
					', [$this->EbookId], EbookTag::class);
		}

		return $this->_Tags;
	}

	/**
	 * @return array<LocSubject>
	 */
	protected function GetLocSubjects(): array{
		if(!isset($this->_LocSubjects)){
			$this->_LocSubjects = Db::Query('
							SELECT l.*
							from LocSubjects l
							inner join EbookLocSubjects el using (LocSubjectId)
							where EbookId = ?
							order by SortOrder asc
					', [$this->EbookId], LocSubject::class);
		}

		return $this->_LocSubjects;
	}

	/**
	 * @return array<CollectionMembership>
	 */
	protected function GetCollectionMemberships(): array{
		if(!isset($this->_CollectionMemberships)){
			$this->_CollectionMemberships = Db::Query('
							SELECT *
							from CollectionEbooks
							where EbookId = ?
							order by SortOrder asc
						', [$this->EbookId], CollectionMembership::class);
		}

		return $this->_CollectionMemberships;
	}

	/**
	 * @return array<EbookSource>
	 */
	protected function GetSources(): array{
		if(!isset($this->_Sources)){
			$this->_Sources = Db::Query('
						SELECT *
						from EbookSources
						where EbookId = ?
						order by SortOrder asc
					', [$this->EbookId], EbookSource::class);
		}

		return $this->_Sources;
	}

	/**
	 * Fill all contributor properties for this ebook, e.g. authors, translators, etc.
	 *
	 * We do this in a single database query to prevent 4+ queries for each ebook.
	 */
	protected function GetAllContributors(): void{
		$contributors = Db::Query('
						SELECT *
						from Contributors
						where EbookId = ?
						order by MarcRole asc, SortOrder asc
					', [$this->EbookId], Contributor::class);

		$this->_Authors = [];
		$this->_Translators = [];
		$this->_Illustrators = [];
		$this->_Contributors = [];

		foreach($contributors as $contributor){
			switch($contributor->MarcRole){
				case Enums\MarcRole::Author:
					$this->_Authors[] = $contributor;

					break;

				case Enums\MarcRole::Translator:
					$this->_Translators[] = $contributor;

					break;

				case Enums\MarcRole::Illustrator:
					$this->_Illustrators[] = $contributor;

					break;

				case Enums\MarcRole::Contributor:
					$this->_Contributors[] = $contributor;

					break;
			}
		}
	}

	/**
	 * @return array<Contributor>
	 */
	protected function GetAuthors(): array{
		if(!isset($this->_Authors)){
			$this->GetAllContributors();
		}

		return $this->_Authors;
	}

	/**
	 * @return array<Contributor>
	 */
	protected function GetIllustrators(): array{
		if(!isset($this->_Illustrators)){
			$this->GetAllContributors();
		}

		return $this->_Illustrators;
	}

	/**
	 * @return array<Contributor>
	 */
	protected function GetTranslators(): array{
		if(!isset($this->_Translators)){
			$this->GetAllContributors();
		}

		return $this->_Translators;
	}

	/**
	 * @return array<Contributor>
	 */
	protected function GetContributors(): array{
		if(!isset($this->_Contributors)){
			$this->GetAllContributors();
		}

		return $this->_Contributors;
	}

	/**
	 * @return ?array<string>
	 */
	protected function GetTocEntries(): ?array{
		if(!isset($this->_TocEntries)){
			$this->_TocEntries = [];

			$result = Db::Query('
					SELECT *
					from TocEntries
					where EbookId = ?
					order by SortOrder asc
				', [$this->EbookId]);

			foreach($result as $row){
				$this->_TocEntries[] = $row->TocEntry;
			}

			if(sizeof($this->_TocEntries) == 0){
				$this->_TocEntries = null;
			}
		}

		return $this->_TocEntries;
	}

	protected function GetUrl(): string{
		if(!isset($this->_Url)){
			$this->_Url = str_replace(EBOOKS_IDENTIFIER_ROOT, '', $this->Identifier);
		}

		return $this->_Url;
	}

	protected function GetHasDownloads(): bool{
		if(!isset($this->_HasDownloads)){
			$this->_HasDownloads = $this->EpubUrl || $this->AdvancedEpubUrl || $this->KepubUrl || $this->Azw3Url;
		}

		return $this->_HasDownloads;
	}

	protected function GetUrlSafeIdentifier(): string{
		if(!isset($this->_UrlSafeIdentifier)){
			$this->_UrlSafeIdentifier = str_replace(['url:https://standardebooks.org/ebooks/', '/'], ['', '_'], $this->Identifier);
		}

		return $this->_UrlSafeIdentifier;
	}

	protected function GetHeroImageUrl(): string{
		if(!isset($this->_HeroImageUrl)){
			$this->_HeroImageUrl = '/images/covers/' . $this->UrlSafeIdentifier . '-' . substr(sha1($this->Updated->format(Enums\DateTimeFormat::UnixTimestamp->value)), 0, 8) . '-hero.jpg';
		}

		return $this->_HeroImageUrl;
	}

	protected function GetHeroImageAvifUrl(): ?string{
		if(!isset($this->_HeroImageAvifUrl)){
			if(file_exists(WEB_ROOT . '/images/covers/' . $this->UrlSafeIdentifier . '-hero.avif')){
				$this->_HeroImageAvifUrl = '/images/covers/' . $this->UrlSafeIdentifier . '-' . substr(sha1($this->Updated->format(Enums\DateTimeFormat::UnixTimestamp->value)), 0, 8) . '-hero.avif';
			}
		}

		return $this->_HeroImageAvifUrl;
	}

	protected function GetHeroImage2xUrl(): string{
		if(!isset($this->_HeroImage2xUrl)){
			$this->_HeroImage2xUrl = '/images/covers/' . $this->UrlSafeIdentifier . '-' . substr(sha1($this->Updated->format(Enums\DateTimeFormat::UnixTimestamp->value)), 0, 8) . '-hero@2x.jpg';
		}

		return $this->_HeroImage2xUrl;
	}

	protected function GetHeroImage2xAvifUrl(): ?string{
		if(!isset($this->_HeroImage2xAvifUrl)){
			if(file_exists(WEB_ROOT . '/images/covers/' . $this->UrlSafeIdentifier . '-hero@2x.avif')){
				$this->_HeroImage2xAvifUrl = '/images/covers/' . $this->UrlSafeIdentifier . '-' . substr(sha1($this->Updated->format(Enums\DateTimeFormat::UnixTimestamp->value)), 0, 8) . '-hero@2x.avif';
			}
		}

		return $this->_HeroImage2xAvifUrl;
	}

	protected function GetCoverImageUrl(): string{
		if(!isset($this->_CoverImageUrl)){
			$this->_CoverImageUrl = '/images/covers/' . $this->UrlSafeIdentifier . '-' . substr(sha1($this->Updated->format(Enums\DateTimeFormat::UnixTimestamp->value)), 0, 8) . '-cover.jpg';
		}

		return $this->_CoverImageUrl;
	}

	protected function GetCoverImageAvifUrl(): ?string{
		if(!isset($this->_CoverImageAvifUrl)){
			if(file_exists(WEB_ROOT . '/images/covers/' . $this->UrlSafeIdentifier . '-cover.avif')){
				$this->_CoverImageAvifUrl = '/images/covers/' . $this->UrlSafeIdentifier . '-' . substr(sha1($this->Updated->format(Enums\DateTimeFormat::UnixTimestamp->value)), 0, 8) . '-cover.avif';
			}
		}

		return $this->_CoverImageAvifUrl;
	}

	protected function GetCoverImage2xUrl(): string{
		if(!isset($this->_CoverImage2xUrl)){
			$this->_CoverImage2xUrl = '/images/covers/' . $this->UrlSafeIdentifier . '-' . substr(sha1($this->Updated->format(Enums\DateTimeFormat::UnixTimestamp->value)), 0, 8) . '-cover@2x.jpg';
		}

		return $this->_CoverImage2xUrl;
	}

	protected function GetCoverImage2xAvifUrl(): ?string{
		if(!isset($this->_CoverImage2xAvifUrl)){
			if(file_exists(WEB_ROOT . '/images/covers/' . $this->UrlSafeIdentifier . '-cover@2x.avif')){
				$this->_CoverImage2xAvifUrl = '/images/covers/' . $this->UrlSafeIdentifier . '-' . substr(sha1($this->Updated->format(Enums\DateTimeFormat::UnixTimestamp->value)), 0, 8) . '-cover@2x.avif';
			}
		}

		return $this->_CoverImage2xAvifUrl;
	}

	protected function GetReadingEaseDescription(): string{
		if(!isset($this->_ReadingEaseDescription)){
			if($this->ReadingEase > 89){
				$this->_ReadingEaseDescription = 'very easy';
			}
			elseif($this->ReadingEase >= 79 && $this->ReadingEase <= 89){
				$this->_ReadingEaseDescription = 'easy';
			}
			elseif($this->ReadingEase > 69 && $this->ReadingEase <= 79){
				$this->_ReadingEaseDescription = 'fairly easy';
			}
			elseif($this->ReadingEase > 59 && $this->ReadingEase <= 69){
				$this->_ReadingEaseDescription = 'average difficulty';
			}
			elseif($this->ReadingEase > 49 && $this->ReadingEase <= 59){
				$this->_ReadingEaseDescription = 'fairly difficult';
			}
			elseif($this->ReadingEase > 39 && $this->ReadingEase <= 49){
				$this->_ReadingEaseDescription = 'difficult';
			}
			else{
				$this->_ReadingEaseDescription = 'very difficult';
			}
		}

		return $this->_ReadingEaseDescription;
	}

	protected function GetReadingTime(): string{
		if(!isset($this->_ReadingTime)){
			$readingTime = ceil(($this->WordCount ?? 0) / AVERAGE_READING_WORDS_PER_MINUTE);
			$this->_ReadingTime = (string)$readingTime;

			if($readingTime < 60){
				$this->_ReadingTime .= ' minute';
				if($readingTime != 1){
					$this->_ReadingTime .= 's';
				}
			}
			else{
				$readingTimeHours = floor($readingTime / 60);
				$readingTimeMinutes = ceil($readingTime % 60);
				$this->_ReadingTime = $readingTimeHours . ' hour';
				if($readingTimeHours != 1){
					$this->_ReadingTime .= 's';
				}

				if($readingTimeMinutes != 0){
					$this->_ReadingTime .= ' ' . $readingTimeMinutes . ' minute';
					if($readingTimeMinutes != 1){
						$this->_ReadingTime .= 's';
					}
				}
			}
		}

		return $this->_ReadingTime;
	}

	protected function GetAuthorsHtml(): string{
		if(!isset($this->_AuthorsHtml)){
			$this->_AuthorsHtml = Ebook::GenerateContributorList($this->Authors, true);
		}

		return $this->_AuthorsHtml;
	}

	protected function GetAuthorsUrl(): string{
		if(!isset($this->_AuthorsUrl)){
			$this->_AuthorsUrl = preg_replace('|url:https://standardebooks.org/ebooks/([^/]+)/.*|ius', '/ebooks/\1', $this->Identifier);
		}

		return $this->_AuthorsUrl;
	}

	protected function GetContributorsHtml(): string{
		if(!isset($this->_ContributorsHtml)){
			$this->_ContributorsHtml = '';
			if(sizeof($this->Contributors) > 0){
				$this->_ContributorsHtml .= ' with ' . Ebook::GenerateContributorList($this->Contributors, false) . ';';
			}

			if(sizeof($this->Translators) > 0){
				$this->_ContributorsHtml .= ' translated by ' . Ebook::GenerateContributorList($this->Translators, false) . ';';
			}

			if(sizeof($this->Illustrators) > 0){
				$this->_ContributorsHtml .= ' illustrated by ' . Ebook::GenerateContributorList($this->Illustrators, false) . ';';
			}

			if(!empty($this->_ContributorsHtml)){
				$this->_ContributorsHtml = ucfirst(rtrim(trim($this->_ContributorsHtml), ';'));

				if(substr(strip_tags($this->_ContributorsHtml), -1) != '.'){
					$this->_ContributorsHtml .= '.';
				}
			}
		}

		return $this->_ContributorsHtml;
	}

	protected function GetTitleWithCreditsHtml(): string{
		if(!isset($this->_TitleWithCreditsHtml)){
			$titleContributors = '';
			if(sizeof($this->Contributors) > 0){
				$titleContributors .= '. With ' . Ebook::GenerateContributorList($this->Contributors, false);
			}

			if(sizeof($this->Translators) > 0){
				$titleContributors .= '. Translated by ' . Ebook::GenerateContributorList($this->Translators, false);
			}

			if(sizeof($this->Illustrators) > 0){
				$titleContributors .= '. Illustrated by ' . Ebook::GenerateContributorList($this->Illustrators, false);
			}

			$this->_TitleWithCreditsHtml = Formatter::EscapeHtml($this->Title) . ', by ' . str_replace('&amp;', '&', $this->AuthorsHtml . $titleContributors);
		}

		return $this->_TitleWithCreditsHtml;
	}

	protected function GetTextUrl(): string{
		if(!isset($this->_TextUrl)){
			$this->_TextUrl = $this->Url . '/text';
		}

		return $this->_TextUrl;
	}

	protected function GetTextSinglePageUrl(): string{
		if(!isset($this->_TextSinglePageUrl)){
			$this->_TextSinglePageUrl = $this->Url . '/text/single-page';
		}

		return $this->_TextSinglePageUrl;
	}

	protected function GetTextSinglePageSizeFormatted(): string{
		if(!isset($this->_TextSinglePageSizeFormatted)){
			$bytes = $this->TextSinglePageByteCount;
			$sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

			$index = 0;
			while($bytes >= 1024 && $index < count($sizes) - 1){
				$bytes /= 1024;
				$index++;
			}

			if($index == 0){
				// No decimal point for smaller than a KB.
				$this->_TextSinglePageSizeFormatted = sprintf("%d %s", $bytes, $sizes[$index]);
			}else{
				$this->_TextSinglePageSizeFormatted = sprintf("%.1f %s", $bytes, $sizes[$index]);
			}
		}

		return $this->_TextSinglePageSizeFormatted;
	}

	protected function GetIndexableText(): string{
		if(!isset($this->_IndexableText)){
			$this->_IndexableText = $this->FullTitle ?? $this->Title;

			$this->_IndexableText .= ' ' . $this->AlternateTitle;

			foreach($this->CollectionMemberships as $collectionMembership){
				$this->_IndexableText .= ' ' . $collectionMembership->Collection->Name;
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

			// Remove diacritics and non-alphanumeric characters.
			$this->_IndexableText = trim(preg_replace('|[^a-zA-Z0-9 ]|ius', ' ', Formatter::RemoveDiacritics($this->_IndexableText)));
		}

		return $this->_IndexableText;
	}

	protected function GetEbookPlaceholder(): ?EbookPlaceholder{
		if(!isset($this->_EbookPlaceholder)){
			if(!isset($this->EbookId)){
				$this->_EbookPlaceholder = null;
			}
			else{
				$this->_EbookPlaceholder = Db::Query('
								SELECT *
								from EbookPlaceholders
								where EbookId = ?
							', [$this->EbookId], EbookPlaceholder::class)[0] ?? null;
			}
		}

		return $this->_EbookPlaceholder;
	}


	// ***********
	// ORM METHODS
	// ***********

	/**
	 * Construct an Ebook from a filesystem path.
	 *
	 * @param string $wwwFilesystemPath The valid readable filesytem path where the ebook is served on the web.
	 *
	 * @return Ebook The populated Ebook object.
	 *
	 * @throws Exceptions\EbookNotFoundException
	 * @throws Exceptions\EbookParsingException
	 * @throws Exceptions\InvalidEbookWwwFilesystemPathException
	 * @throws Exceptions\InvalidGitCommitException
	 */
	public static function FromFilesystem(?string $wwwFilesystemPath = null): Ebook{
		if($wwwFilesystemPath === null){
			throw new Exceptions\InvalidEbookWwwFilesystemPathException($wwwFilesystemPath);
		}

		$ebook = new Ebook();

		// First, construct a source repo path from our WWW filesystem path.
		if(is_dir($wwwFilesystemPath . '/.git')){
			$wwwFilesystemPath = $wwwFilesystemPath . '/src/epub';
			$ebook->RepoFilesystemPath = $wwwFilesystemPath;
		}
		else{
			$ebook->RepoFilesystemPath = str_replace(EBOOKS_DIST_PATH, '', $wwwFilesystemPath);
			$ebook->RepoFilesystemPath = SITE_ROOT . '/ebooks/' . str_replace('/', '_', $ebook->RepoFilesystemPath) . '.git';
		}

		if(!is_dir($ebook->RepoFilesystemPath)){ // On dev systems we might not have the bare repos, so make an adjustment.
			try{
				$ebook->RepoFilesystemPath = preg_replace('/\.git$/ius', '', $ebook->RepoFilesystemPath);
			}
			catch(\Exception){
				// We may get an exception from preg_replace if the passed repo wwwFilesystemPath contains invalid UTF-8 characters, whichis  a common injection attack vector.
				throw new Exceptions\EbookNotFoundException('Invalid repo filesystem path: ' . $ebook->RepoFilesystemPath);
			}
		}

		if(!is_dir($wwwFilesystemPath)){
			throw new Exceptions\EbookNotFoundException('Invalid www filesystem path: ' . $wwwFilesystemPath);
		}

		if(!is_dir($ebook->RepoFilesystemPath)){
			throw new Exceptions\EbookNotFoundException('Invalid repo filesystem path: ' . $ebook->RepoFilesystemPath);
		}

		if(!is_file($wwwFilesystemPath . '/content.opf')){
			throw new Exceptions\EbookNotFoundException('Invalid content.opf file: ' . $wwwFilesystemPath . '/content.opf');
		}

		$ebook->WwwFilesystemPath = $wwwFilesystemPath;

		$rawMetadata = file_get_contents($wwwFilesystemPath . '/content.opf');

		// Get the SE identifier.
		preg_match('|<dc:identifier[^>]*?>(.+?)</dc:identifier>|ius', $rawMetadata, $matches);
		if(sizeof($matches) != 2){
			throw new Exceptions\EbookParsingException('Invalid <dc:identifier> element.');
		}
		$ebook->Identifier = (string)$matches[1];

		try{
			// PHP Safe throws an exception from filesize() if the file doesn't exist, but PHP still emits a warning. So, just silence the warning.
			$ebook->TextSinglePageByteCount = @filesize($ebook->WwwFilesystemPath . '/text/single-page.xhtml');
		}
		catch(\Exception){
			// Single page file doesn't exist, just pass.
		}

		// Generate the Kindle cover URL.
		$tempPath = glob($ebook->WwwFilesystemPath . '/downloads/*_EBOK_portrait.jpg');
		if(sizeof($tempPath) > 0){
			$ebook->KindleCoverUrl = $ebook->Url . '/downloads/' . basename($tempPath[0]);
		}

		// Generate the compatible epub URL.
		$tempPath = glob($ebook->WwwFilesystemPath . '/downloads/*.epub');
		if(sizeof($tempPath) > 0){
			$ebook->EpubUrl = $ebook->Url . '/downloads/' . basename($tempPath[0]);
		}

		// Generate the epub URL.
		$tempPath = glob($ebook->WwwFilesystemPath . '/downloads/*_advanced.epub');
		if(sizeof($tempPath) > 0){
			$ebook->AdvancedEpubUrl = $ebook->Url . '/downloads/' . basename($tempPath[0]);
		}

		// Generate the Kepub URL.
		$tempPath = glob($ebook->WwwFilesystemPath . '/downloads/*.kepub.epub');
		if(sizeof($tempPath) > 0){
			$ebook->KepubUrl = $ebook->Url . '/downloads/' . basename($tempPath[0]);
		}

		// Generate the azw3 URL.
		$tempPath = glob($ebook->WwwFilesystemPath . '/downloads/*.azw3');
		if(sizeof($tempPath) > 0){
			$ebook->Azw3Url = $ebook->Url . '/downloads/' . basename($tempPath[0]);
		}

		$tempPath = glob($ebook->WwwFilesystemPath . '/downloads/cover.jpg');
		if(sizeof($tempPath) > 0){
			$ebook->DistCoverUrl = $ebook->Url . '/downloads/' . basename($tempPath[0]);
		}

		// Fill in the short history of this repo.
		try{
			$historyEntries = explode("\n",  shell_exec('cd ' . escapeshellarg($ebook->RepoFilesystemPath) . ' && git log -n5 --pretty=format:"%ct %H %s"'));

			$gitCommits = [];
			foreach($historyEntries as $logLine){
				$gitCommits[] = GitCommit::FromLogLine($logLine);
			}
			$ebook->GitCommits = $gitCommits;
		}
		catch(\Safe\Exceptions\ExecException){
			// Pass.
		}

		// Now do some heavy XML lifting!
		try{
			$xml = new SimpleXMLElement(str_replace('xmlns=', 'ns=', $rawMetadata));
		}
		catch(\Exception $ex){
			throw new Exceptions\EbookParsingException($ex->getMessage());
		}

		$xml->registerXPathNamespace('dc', 'http://purl.org/dc/elements/1.1/');

		$ebook->Title = trim(Ebook::NullIfEmpty($xml->xpath('/package/metadata/dc:title')) ?? '');
		if($ebook->Title == ''){
			throw new Exceptions\EbookParsingException('Invalid <dc:title> element.');
		}

		$ebook->Title = str_replace('\'', '’', $ebook->Title);

		$ebook->FullTitle = Ebook::NullIfEmpty($xml->xpath('/package/metadata/dc:title[@id="fulltitle"]'));

		$ebook->AlternateTitle = Ebook::NullIfEmpty($xml->xpath('/package/metadata/meta[@property="dcterms:alternate"][@refines="#title"]'));

		$date = $xml->xpath('/package/metadata/dc:date') ?: [];
		if($date !== false && sizeof($date) > 0){
			/** @throws void */
			$ebook->EbookCreated = new DateTimeImmutable((string)$date[0]);
		}

		$modifiedDate = $xml->xpath('/package/metadata/meta[@property="dcterms:modified"]') ?: [];
		if($modifiedDate !== false && sizeof($modifiedDate) > 0){
			/** @throws void */
			$ebook->EbookUpdated = new DateTimeImmutable((string)$modifiedDate[0]);
		}

		// Get SE tags.
		$tags = [];
		foreach($xml->xpath('/package/metadata/meta[@property="se:subject"]') ?: [] as $tag){
			$ebookTag = new EbookTag();
			$ebookTag->Name = $tag;
			$ebookTag->UrlName = Formatter::MakeUrlSafe($ebookTag->Name);
			$tags[] = $ebookTag;
		}
		$ebook->Tags = $tags;

		$includeToc = sizeof($xml->xpath('/package/metadata/meta[@property="se:is-a-collection"]') ?: []) > 0;

		// Fill the ToC if necessary.
		if($includeToc){
			$tocEntries = [];
			try{
				$tocDom = new SimpleXMLElement(str_replace('xmlns=', 'ns=', file_get_contents($wwwFilesystemPath . '/toc.xhtml')));
			}
			catch(\Exception $ex){
				throw new Exceptions\EbookParsingException($ex->getMessage());
			}
			$tocDom->registerXPathNamespace('epub', 'http://www.idpf.org/2007/ops');
			foreach($tocDom->xpath('/html/body//nav[@epub:type="toc"]//a[not(contains(@epub:type, "z3998:roman")) and not(text() = "Titlepage" or text() = "Imprint" or text() = "Colophon" or text() = "Endnotes" or text() = "Uncopyright") and not(contains(@href, "halftitle"))]') ?: [] as $item){
				$tocEntries[] = (string)$item;
			}
			$ebook->TocEntries = $tocEntries;
		}

		// Get SE collections.
		$collectionMemberships = [];
		foreach($xml->xpath('/package/metadata/meta[@property="belongs-to-collection"]') ?: [] as $collection){
			$cm = new CollectionMembership();
			$cm->Collection = Collection::FromName($collection);

			$id = $collection->attributes()->id ?? '';
			foreach($xml->xpath('/package/metadata/meta[@refines="#' . $id . '"][@property="group-position"]') ?: [] as $s){
				$cm->SequenceNumber = (int)$s;
			}
			foreach($xml->xpath('/package/metadata/meta[@refines="#' . $id . '"][@property="collection-type"]') ?: [] as $s){
				$cm->Collection->Type = Enums\CollectionType::tryFrom((string)$s) ?? Enums\CollectionType::Unknown;
			}
			$collectionMemberships[] = $cm;
		}
		$ebook->CollectionMemberships = $collectionMemberships;

		// Get LoC tags.
		$locSubjects = [];
		foreach($xml->xpath('/package/metadata/dc:subject') ?: [] as $subject){
			$locSubject = new LocSubject();
			$locSubject->Name = $subject;
			$locSubjects[] = $locSubject;
		}
		$ebook->LocSubjects = $locSubjects;

		// Figure out authors and contributors.
		$authors = [];
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

			$contributor = new Contributor();
			$contributor->Name = (string)$author;
			$contributor->UrlName = Formatter::MakeUrlSafe($contributor->Name);
			$contributor->SortName = $fileAs;
			$contributor->FullName = Ebook::NullIfEmpty($xml->xpath('/package/metadata/meta[@property="se:name.person.full-name"][@refines="#' . $id . '"]'));
			$contributor->WikipediaUrl = Ebook::NullIfEmpty($xml->xpath('/package/metadata/meta[@property="se:url.encyclopedia.wikipedia"][@refines="#' . $id . '"]'));
			$contributor->MarcRole = Enums\MarcRole::Author;
			$contributor->NacoafUrl = Ebook::NullIfEmpty($xml->xpath('/package/metadata/meta[@property="se:url.authority.nacoaf"][@refines="#' . $id . '"]'));

			$authors[] = $contributor;
		}
		if(sizeof($authors) == 0){
			throw new Exceptions\EbookParsingException('Invalid <dc:creator> element.');
		}

		$ebook->Authors = $authors;

		$illustrators = [];
		$translators = [];
		$contributors = [];
		foreach($xml->xpath('/package/metadata/dc:contributor') ?: [] as $contributor){
			$id = '';
			if($contributor->attributes() !== null){
				$id = $contributor->attributes()->id;
			}

			foreach($xml->xpath('/package/metadata/meta[ (@property="role" or @property="se:role") and @refines="#' . $id . '"]') ?: [] as $role){
				$c = new Contributor();
				$c->Name = (string)$contributor;
				$c->UrlName = Formatter::MakeUrlSafe($contributor->Name);
				$c->SortName = Ebook::NullIfEmpty($xml->xpath('/package/metadata/meta[@property="file-as"][@refines="#' . $id . '"]'));
				$c->FullName = Ebook::NullIfEmpty($xml->xpath('/package/metadata/meta[@property="se:name.person.full-name"][@refines="#' . $id . '"]'));
				$c->WikipediaUrl = Ebook::NullIfEmpty($xml->xpath('/package/metadata/meta[@property="se:url.encyclopedia.wikipedia"][@refines="#' . $id . '"]'));
				$c->MarcRole = Enums\MarcRole::tryFrom((string)$role) ?? Enums\MarcRole::Contributor;
				$c->NacoafUrl = Ebook::NullIfEmpty($xml->xpath('/package/metadata/meta[@property="se:url.authority.nacoaf"][@refines="#' . $id . '"]'));

				// A display-sequence of 0 indicates that we don't want to process this contributor.
				$displaySequence = Ebook::NullIfEmpty($xml->xpath('/package/metadata/meta[@property="display-seq"][@refines="#' . $id . '"]'));
				if($displaySequence !== '0'){
					if($role == 'trl'){
						$translators[] = $c;
					}

					if($role == 'ill'){
						$illustrators[] = $c;
					}

					if($role == 'ctb'){
						$contributors[] = $c;
					}
				}
			}

			// If we added an illustrator who is also the translator, remove the illustrator credit so the name doesn't appear twice.
			foreach($illustrators as $key => $illustrator){
				foreach($translators as $translator){
					if($translator->Name == $illustrator->Name){
						unset($illustrators[$key]);
						break;
					}
				}
			}

		}
		$ebook->Illustrators = $illustrators;
		$ebook->Translators = $translators;
		$ebook->Contributors = $contributors;

		// Some basic data.
		$ebook->Description = Ebook::NullIfEmpty($xml->xpath('/package/metadata/dc:description')) ?? '';
		$ebook->LongDescription = Ebook::NullIfEmpty($xml->xpath('/package/metadata/meta[@property="se:long-description"]')) ?? '';
		$ebook->Language = Ebook::NullIfEmpty($xml->xpath('/package/metadata/dc:language')) ?? '';

		$wordCount = 0;
		$wordCountElement = $xml->xpath('/package/metadata/meta[@property="se:word-count"]');
		if($wordCountElement !== false && sizeof($wordCountElement) > 0){
			$wordCount = (int)$wordCountElement[0];
		}
		$ebook->WordCount = $wordCount;

		$readingEase = 0;
		$readingEaseElement = $xml->xpath('/package/metadata/meta[@property="se:reading-ease.flesch"]');
		if($readingEaseElement !== false && sizeof($readingEaseElement) > 0){
			$readingEase = (float)$readingEaseElement[0];
		}
		$ebook->ReadingEase = $readingEase;

		// First the Wikipedia URLs.
		$ebook->WikipediaUrl = Ebook::NullIfEmpty($xml->xpath('/package/metadata/meta[@property="se:url.encyclopedia.wikipedia"][not(@refines)]'));

		// Next the page scan source URLs.
		$sources = [];
		foreach($xml->xpath('/package/metadata/dc:source') ?: [] as $element){
			$ebookSource = new EbookSource();
			$ebookSource->Url = (string)$element;
			$ebookSource->Type = Enums\EbookSourceType::Other;

			if(mb_stripos($ebookSource->Url, 'gutenberg.org/') !== false){
				$ebookSource->Type = Enums\EbookSourceType::ProjectGutenberg;
			}
			elseif(mb_stripos($ebookSource->Url, 'gutenberg.net.au/') !== false){
				$ebookSource->Type = Enums\EbookSourceType::ProjectGutenbergAustralia;
			}
			elseif(mb_stripos($ebookSource->Url, 'gutenberg.ca/') !== false){
				$ebookSource->Type = Enums\EbookSourceType::ProjectGutenbergCanada;
			}
			elseif(mb_stripos($ebookSource->Url, 'archive.org/details') !== false){
				// `/details` excludes Wayback Machine URLs which may sometimes occur, for example in Lyrical Ballads.
				$ebookSource->Type = Enums\EbookSourceType::InternetArchive;
			}
			elseif(mb_stripos($ebookSource->Url, 'hathitrust.org/') !== false){
				$ebookSource->Type = Enums\EbookSourceType::HathiTrust;
			}
			elseif(mb_stripos($ebookSource->Url, 'wikisource.org/') !== false){
				$ebookSource->Type = Enums\EbookSourceType::Wikisource;
			}
			elseif(mb_stripos($ebookSource->Url, 'books.google.com/') !== false || mb_stripos($ebookSource->Url, 'google.com/books/') !== false){
				$ebookSource->Type = Enums\EbookSourceType::GoogleBooks;
			}
			elseif(mb_stripos($ebookSource->Url, 'www.fadedpage.com') !== false){
				$ebookSource->Type = Enums\EbookSourceType::FadedPage;
			}

			$sources[] = $ebookSource;
		}
		$ebook->Sources = $sources;

		// Next the GitHub URLs.
		$ebook->GitHubUrl = Ebook::NullIfEmpty($xml->xpath('/package/metadata/meta[@property="se:url.vcs.github"][not(@refines)]'));

		return $ebook;
	}

	/**
	 * Joins the `Name` properites of `Contributor` objects as a URL slug, e.g.,
	 *
	 * 	```
	 * 	([0] => Contributor Object ([Name] => William Wordsworth), ([1] => Contributor Object ([Name] => Samuel Coleridge)))
	 * 	```
	 *
	 * returns `william-wordsworth_samuel-taylor-coleridge`.
	 *
	 * @param array<Contributor> $contributors
	 */
	protected static function GetContributorsUrlSlug(array $contributors): string{
		return implode('_', array_map('Formatter::MakeUrlSafe', array_column($contributors, 'Name')));
	}

	/**
	 * Populates the `Identifier` property based on the `Title`, `Authors`, `Translators`, and `Illustrators`. Used when creating ebook placeholders.
	 *
	 * @throws Exceptions\InvalidEbookIdentifierException
	 */
	public function FillIdentifierFromTitleAndContributors(): void{
		if(!isset($this->Authors) || sizeof($this->Authors) == 0){
			throw new Exceptions\InvalidEbookIdentifierException('Authors required');
		}

		if(!isset($this->Title)){
			throw new Exceptions\InvalidEbookIdentifierException('Title required');
		}

		$authorString = Ebook::GetContributorsUrlSlug($this->Authors);
		$titleString = Formatter::MakeUrlSafe($this->Title);
		$translatorString = '';
		$illustratorString = '';

		if(isset($this->Translators) && sizeof($this->Translators) > 0){
			$translatorString = Ebook::GetContributorsUrlSlug($this->Translators);
		}

		if(isset($this->Illustrators) && sizeof($this->Illustrators) > 0){
			$illustratorString = Ebook::GetContributorsUrlSlug($this->Illustrators);
		}

		$this->Identifier = EBOOKS_IDENTIFIER_PREFIX . $authorString . '/' . $titleString;

		if($translatorString != ''){
			$this->Identifier .= '/' . $translatorString;
		}

		if($illustratorString != ''){
			$this->Identifier .= '/' . $illustratorString;
		}
	}


	// *******
	// METHODS
	// *******

	/**
	 * @throws Exceptions\InvalidEbookException
	 */
	public function Validate(): void{
		$error = new Exceptions\InvalidEbookException();

		if(isset($this->Identifier)){
			$this->Identifier = trim($this->Identifier);

			if($this->Identifier == ''){
				$error->Add(new Exceptions\EbookIdentifierRequiredException());
			}

			if(strlen($this->Identifier) > EBOOKS_MAX_LONG_STRING_LENGTH){
				$error->Add(new Exceptions\StringTooLongException('Ebook Identifier'));
			}
		}
		else{
			$error->Add(new Exceptions\EbookIdentifierRequiredException());
		}

		$this->WwwFilesystemPath = trim($this->WwwFilesystemPath ?? '');
		if($this->WwwFilesystemPath == ''){
			$this->WwwFilesystemPath = null;
		}

		if(isset($this->WwwFilesystemPath)){
			if(strlen($this->WwwFilesystemPath) > EBOOKS_MAX_LONG_STRING_LENGTH){
				$error->Add(new Exceptions\StringTooLongException('Ebook WwwFilesystemPath'));
			}

			if(!is_readable($this->WwwFilesystemPath)){
				$error->Add(new Exceptions\InvalidEbookWwwFilesystemPathException($this->WwwFilesystemPath));
			}
		}

		$this->RepoFilesystemPath = trim($this->RepoFilesystemPath ?? '');
		if($this->RepoFilesystemPath == ''){
			$this->RepoFilesystemPath = null;
		}

		if(isset($this->RepoFilesystemPath)){
			if(strlen($this->RepoFilesystemPath) > EBOOKS_MAX_LONG_STRING_LENGTH){
				$error->Add(new Exceptions\StringTooLongException('Ebook RepoFilesystemPath'));
			}

			if(!is_readable($this->RepoFilesystemPath)){
				$error->Add(new Exceptions\InvalidEbookRepoFilesystemPathException($this->RepoFilesystemPath));
			}
		}

		$this->KindleCoverUrl = trim($this->KindleCoverUrl ?? '');
		if($this->KindleCoverUrl == ''){
			$this->KindleCoverUrl = null;
		}

		if(isset($this->KindleCoverUrl)){
			if(!preg_match('|/*_EBOK_portrait.jpg$|ius', $this->KindleCoverUrl)){
				$error->Add(new Exceptions\InvalidEbookKindleCoverUrlException('Invalid Ebook KindleCoverUrl: ' . $this->KindleCoverUrl));
			}

			if(strlen($this->KindleCoverUrl) > EBOOKS_MAX_LONG_STRING_LENGTH){
				$error->Add(new Exceptions\StringTooLongException('Ebook KindleCoverUrl'));
			}
		}

		$this->EpubUrl = trim($this->EpubUrl ?? '');
		if($this->EpubUrl == ''){
			$this->EpubUrl = null;
		}

		if(isset($this->EpubUrl)){
			if(!preg_match('|/*.epub$|ius', $this->EpubUrl)){
				$error->Add(new Exceptions\InvalidEbookEpubUrlException('Invalid Ebook EpubUrl: ' . $this->EpubUrl));
			}

			if(strlen($this->EpubUrl) > EBOOKS_MAX_LONG_STRING_LENGTH){
				$error->Add(new Exceptions\StringTooLongException('Ebook EpubUrl'));
			}
		}

		$this->AdvancedEpubUrl = trim($this->AdvancedEpubUrl ?? '');
		if($this->AdvancedEpubUrl == ''){
			$this->AdvancedEpubUrl = null;
		}

		if(isset($this->AdvancedEpubUrl)){
			if(!preg_match('|/*_advanced.epub$|ius', $this->AdvancedEpubUrl)){
				$error->Add(new Exceptions\InvalidEbookAdvancedEpubUrlException('Invalid Ebook AdvancedEpubUrl: ' . $this->AdvancedEpubUrl));
			}

			if(strlen($this->AdvancedEpubUrl) > EBOOKS_MAX_LONG_STRING_LENGTH){
				$error->Add(new Exceptions\StringTooLongException('Ebook AdvancedEpubUrl'));
			}
		}

		$this->KepubUrl = trim($this->KepubUrl ?? '');
		if($this->KepubUrl == ''){
			$this->KepubUrl = null;
		}

		if(isset($this->KepubUrl)){
			if(!preg_match('|/*.kepub.epub$|ius', $this->KepubUrl)){
				$error->Add(new Exceptions\InvalidEbookKepubUrlException('Invalid Ebook KepubUrl: ' . $this->KepubUrl));
			}

			if(strlen($this->KepubUrl) > EBOOKS_MAX_LONG_STRING_LENGTH){
				$error->Add(new Exceptions\StringTooLongException('Ebook KepubUrl'));
			}
		}

		$this->Azw3Url = trim($this->Azw3Url ?? '');
		if($this->Azw3Url == ''){
			$this->Azw3Url = null;
		}

		if(isset($this->Azw3Url)){
			if(!preg_match('|/*.azw3$|ius', $this->Azw3Url)){
				$error->Add(new Exceptions\InvalidEbookAzw3UrlException('Invalid Ebook Azw3Url: ' . $this->Azw3Url));
			}

			if(strlen($this->Azw3Url) > EBOOKS_MAX_LONG_STRING_LENGTH){
				$error->Add(new Exceptions\StringTooLongException('Ebook Azw3Url'));
			}
		}

		$this->DistCoverUrl = trim($this->DistCoverUrl ?? '');
		if($this->DistCoverUrl == ''){
			$this->DistCoverUrl = null;
		}

		if(isset($this->DistCoverUrl)){
			if(!preg_match('|/*cover.jpg$|ius', $this->DistCoverUrl)){
				$error->Add(new Exceptions\InvalidEbookDistCoverUrlException('Invalid Ebook DistCoverUrl: ' . $this->DistCoverUrl));
			}

			if(strlen($this->DistCoverUrl) > EBOOKS_MAX_LONG_STRING_LENGTH){
				$error->Add(new Exceptions\StringTooLongException('Ebook DistCoverUrl'));
			}
		}

		if(isset($this->Title)){
			$this->Title = trim($this->Title);

			if($this->Title == ''){
				$error->Add(new Exceptions\EbookTitleRequiredException());
			}

			// Sometimes placeholders may have `'` in the title.
			$this->Title = str_replace('\'', '’', $this->Title);

			if(strlen($this->Title) > EBOOKS_MAX_STRING_LENGTH){
				$error->Add(new Exceptions\StringTooLongException('Ebook Title'));
			}
		}
		else{
			$error->Add(new Exceptions\EbookTitleRequiredException());
		}

		$this->FullTitle = trim($this->FullTitle ?? '');
		if($this->FullTitle == ''){
			$this->FullTitle = null;
		}

		if(isset($this->FullTitle) && strlen($this->FullTitle) > EBOOKS_MAX_STRING_LENGTH){
			$error->Add(new Exceptions\StringTooLongException('Ebook FullTitle'));
		}

		$this->AlternateTitle = trim($this->AlternateTitle ?? '');
		if($this->AlternateTitle == ''){
			$this->AlternateTitle = null;
		}

		if(isset($this->AlternateTitle) && strlen($this->AlternateTitle) > EBOOKS_MAX_STRING_LENGTH){
			$error->Add(new Exceptions\StringTooLongException('Ebook AlternateTitle'));
		}

		$this->Description = trim($this->Description ?? '');
		if($this->Description == ''){
			$this->Description = null;
		}

		if(isset($this->Description) && strlen($this->Description) > EBOOKS_MAX_STRING_LENGTH){
			$error->Add(new Exceptions\StringTooLongException('Ebook Description'));
		}

		$this->LongDescription = trim($this->LongDescription ?? '');
		if($this->LongDescription == ''){
			$this->LongDescription = null;
		}

		$this->Language = trim($this->Language ?? '');
		if($this->Language == ''){
			$this->Language = null;
		}

		if(isset($this->Language)){
			if(strlen($this->Language) > 10){
				$error->Add(new Exceptions\StringTooLongException('Ebook Language: ' . $this->Language));
			}
		}

		if(isset($this->WordCount)){
			if($this->WordCount <= 0){
				$error->Add(new Exceptions\InvalidEbookWordCountException('Invalid Ebook WordCount: ' . $this->WordCount));
			}
		}

		if(isset($this->ReadingEase)){
			// In theory, Flesch reading ease can be negative, but in practice it's positive.
			if($this->ReadingEase <= 0){
				$error->Add(new Exceptions\InvalidEbookReadingEaseException('Invalid Ebook ReadingEase: ' . $this->ReadingEase));
			}
		}

		$this->GitHubUrl = trim($this->GitHubUrl ?? '');
		if($this->GitHubUrl == ''){
			$this->GitHubUrl = null;
		}

		if(isset($this->GitHubUrl)){
			if(!preg_match('|^https://github.com/standardebooks/\w+|ius', $this->GitHubUrl)){
				$error->Add(new Exceptions\InvalidEbookGitHubUrlException('Invalid Ebook GitHubUrl: ' . $this->GitHubUrl));
			}

			if(strlen($this->GitHubUrl) > EBOOKS_MAX_STRING_LENGTH){
				$error->Add(new Exceptions\StringTooLongException('Ebook GitHubUrl'));
			}
		}

		$this->WikipediaUrl = trim($this->WikipediaUrl ?? '');
		if($this->WikipediaUrl == ''){
			$this->WikipediaUrl = null;
		}

		if(isset($this->WikipediaUrl)){
			if(!preg_match('|^https://.*wiki.*|ius', $this->WikipediaUrl)){
				$error->Add(new Exceptions\InvalidEbookWikipediaUrlException('Invalid Ebook WikipediaUrl: ' . $this->WikipediaUrl));
			}

			if(strlen($this->WikipediaUrl) > EBOOKS_MAX_STRING_LENGTH){
				$error->Add(new Exceptions\StringTooLongException('Ebook WikipediaUrl'));
			}
		}

		if(isset($this->EbookCreated)){
			if($this->EbookCreated > NOW){
				$error->Add(new Exceptions\InvalidEbookCreatedDatetimeException($this->EbookCreated));
			}
		}

		if(isset($this->EbookUpdated)){
			if($this->EbookUpdated > NOW){
				$error->Add(new Exceptions\InvalidEbookUpdatedDatetimeException($this->EbookUpdated));

			}
		}

		if(isset($this->TextSinglePageByteCount)){
			if($this->TextSinglePageByteCount <= 0){
				$error->Add(new Exceptions\InvalidEbookTextSinglePageByteCountException('Invalid Ebook TextSinglePageByteCount: ' . $this->TextSinglePageByteCount));
			}
		}

		if(isset($this->IndexableText)){
			$this->IndexableText = trim($this->IndexableText ?? '');

			if($this->IndexableText == ''){
				$error->Add(new Exceptions\EbookIndexableTextRequiredException());
			}
		}
		else{
			$error->Add(new Exceptions\EbookIndexableTextRequiredException());
		}

		if(isset($this->EbookPlaceholder)){
			try{
				$this->EbookPlaceholder->Validate();
			}
			catch(Exceptions\ValidationException $ex){
				$error->Add($ex);
			}
		}

		if($this->IsPlaceholder() && !isset($this->EbookPlaceholder)){
			$error->Add(new Exceptions\EbookMissingPlaceholderException());
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	/**
	 * @throws Exceptions\InvalidEbookException
	 * @throws Exceptions\DuplicateEbookException
	 */
	public function CreateOrUpdate(): void{
		try{
			$existingEbook = Ebook::GetByIdentifier($this->Identifier);
			$this->EbookId = $existingEbook->EbookId;
			$this->Save();
		}
		catch(Exceptions\EbookNotFoundException){
			$this->Create();
		}
	}

	/**
	 * @throws Exceptions\InvalidEbookTagException
	 */
	private function CreateTags(): void{
		$tags = [];
		foreach($this->Tags as $ebookTag){
			$tags[] = $ebookTag->GetByNameOrCreate($ebookTag->Name);
		}
		$this->Tags = $tags;
	}

	/**
	 * @throws Exceptions\InvalidLocSubjectException
	 */
	private function CreateLocSubjects(): void{
		$subjects = [];
		foreach($this->LocSubjects as $locSubject){
			$subjects[] = $locSubject->GetByNameOrCreate($locSubject->Name);
		}
		$this->LocSubjects = $subjects;
	}

	/**
	 * @throws Exceptions\InvalidCollectionException
	 */
	private function CreateCollections(): void{
		$collectionMemberships = [];
		foreach($this->CollectionMemberships as $collectionMembership){
			$collection = $collectionMembership->Collection;
			// The updated collection has the CollectionId set for newly-created Collection objects.
			$updatedCollection = $collection->GetByUrlNameOrCreate($collection->UrlName);
			$collectionMembership->Collection = $updatedCollection;
			$collectionMemberships[] = $collectionMembership;
		}
		$this->CollectionMemberships = $collectionMemberships;
	}

	public function GetCollectionPosition(Collection $collection): ?int{
		foreach($this->CollectionMemberships as $cm){
			if($cm->Collection->Name == $collection->Name){
				return $cm->SequenceNumber;
			}
		}

		return null;
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
	private static function GenerateContributorList(array $contributors, bool $includeRdfa): string{
		$string = '';
		$i = 0;

		foreach($contributors as $contributor){
			$role = 'schema:contributor';
			switch($contributor->MarcRole){
				case Enums\MarcRole::Translator:
					$role = 'schema:translator';
					break;
				case Enums\MarcRole::Illustrator:
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
				case Enums\MarcRole::Translator:
					$role = 'schema:translator';
					break;
				case Enums\MarcRole::Illustrator:
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

	public function IsPlaceholder(): bool{
		return $this->WwwFilesystemPath === null;
	}

	/**
	 * If the given list of elements has an element that is not `''`, return that value; otherwise, return `null`.
	 *
	 * @param array<SimpleXMLElement>|false|null $elements
	 */
	private static function NullIfEmpty($elements): ?string{
		if($elements === false){
			return null;
		}

		if(isset($elements[0])){
			$str = (string)$elements[0];
			if($str !== ''){
				return $str;
			}
		}

		return null;
	}

	/**
	 * @throws Exceptions\InvalidEbookException
	 * @throws Exceptions\DuplicateEbookException If an `Ebook` with the given identifier already exists.
	 */
	public function Create(): void{
		$this->Validate();

		try{
			Ebook::GetByIdentifier($this->Identifier);
			throw new Exceptions\DuplicateEbookException($this->Identifier);
		}
		catch(Exceptions\EbookNotFoundException){
			// Pass.
		}

		try{
			$this->CreateTags();
			$this->CreateLocSubjects();
			$this->CreateCollections();
		}
		catch(Exceptions\ValidationException $ex){
			$error = new Exceptions\InvalidEbookException();
			$error->Add($ex);
			throw $error;
		}

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
				$this->EbookCreated, $this->EbookUpdated, $this->TextSinglePageByteCount, $this->IndexableText]);

		$this->EbookId = Db::GetLastInsertedId();

		try{
			$this->AddTags();
			$this->AddLocSubjects();
			$this->AddCollectionMemberships();
			$this->AddGitCommits();
			$this->AddSources();
			$this->AddContributors();
			$this->AddTocEntries();
			$this->AddEbookPlaceholder();
		}
		catch(Exceptions\ValidationException $ex){
			$error = new Exceptions\InvalidEbookException();
			$error->Add($ex);
			throw $error;
		}
	}

	/**
	 * @throws Exceptions\InvalidEbookException
	 */
	public function Save(): void{
		$this->Validate();

		try{
			$this->CreateTags();
			$this->CreateLocSubjects();
			$this->CreateCollections();
		}
		catch(Exceptions\ValidationException $ex){
			$error = new Exceptions\InvalidEbookException();
			$error->Add($ex);
			throw $error;
		}

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
				$this->EbookCreated, $this->EbookUpdated, $this->TextSinglePageByteCount, $this->IndexableText,
				$this->EbookId]);


		try{
			$this->RemoveTags();
			$this->AddTags();

			$this->RemoveLocSubjects();
			$this->AddLocSubjects();

			$this->RemoveCollectionMemberships();
			$this->AddCollectionMemberships();

			$this->RemoveGitCommits();
			$this->AddGitCommits();

			$this->RemoveSources();
			$this->AddSources();

			$this->RemoveContributors();
			$this->AddContributors();

			$this->RemoveTocEntries();
			$this->AddTocEntries();

			$this->RemoveEbookPlaceholder();
			$this->AddEbookPlaceholder();
		}
		catch(Exceptions\ValidationException $ex){
			$error = new Exceptions\InvalidEbookException();
			$error->Add($ex);
			throw $error;
		}
	}

	private function RemoveTags(): void{
		Db::Query('
			DELETE from EbookTags
			where EbookId = ?
		', [$this->EbookId]
		);
	}

	private function AddTags(): void{
		foreach($this->Tags as $sortOrder => $tag){
			try{
				Db::Query('
					INSERT into EbookTags (EbookId, TagId, SortOrder)
					values (?,
						?,
						?)
				', [$this->EbookId, $tag->TagId, $sortOrder]);
			}
			catch(Exceptions\DuplicateDatabaseKeyException){
				// The Ebook already has the Tag, which is fine.
			}
		}
	}

	private function RemoveLocSubjects(): void{
		Db::Query('
			DELETE from EbookLocSubjects
			where EbookId = ?
		', [$this->EbookId]
		);
	}

	private function AddLocSubjects(): void{
		foreach($this->LocSubjects as $sortOrder => $locSubject){
			try{
				Db::Query('
					INSERT into EbookLocSubjects (EbookId, LocSubjectId, SortOrder)
					values (?,
						?,
						?)
				', [$this->EbookId, $locSubject->LocSubjectId, $sortOrder]);
			}
			catch(Exceptions\DuplicateDatabaseKeyException){
				// The Ebook already has the LocSubject, which is fine.
			}
		}
	}

	private function RemoveCollectionMemberships(): void{
		Db::Query('
			DELETE from CollectionEbooks
			where EbookId = ?
		', [$this->EbookId]
		);
	}

	private function AddCollectionMemberships(): void{
		foreach($this->CollectionMemberships as $sortOrder => $collectionMembership){
			$collectionMembership->EbookId = $this->EbookId;
			$collectionMembership->CollectionId = $collectionMembership->Collection->CollectionId;
			$collectionMembership->SortOrder = $sortOrder;

			try{
				Db::Query('
					INSERT into CollectionEbooks (EbookId, CollectionId, SequenceNumber, SortOrder)
					values (?,
						?,
						?,
						?)
				', [$collectionMembership->EbookId, $collectionMembership->CollectionId, $collectionMembership->SequenceNumber,
						$collectionMembership->SortOrder]);
			}
			catch(Exceptions\DuplicateDatabaseKeyException){
				// The Ebook is already a member of this Collection.
			}
		}
	}

	private function RemoveGitCommits(): void{
		Db::Query('
			DELETE from GitCommits
			where EbookId = ?
		', [$this->EbookId]
		);
	}

	/**
	 * @throws Exceptions\InvalidGitCommitException
	 */
	private function AddGitCommits(): void{
		foreach($this->GitCommits as $commit){
			$commit->EbookId = $this->EbookId;
			$commit->Create();
		}
	}

	private function RemoveSources(): void{
		Db::Query('
			DELETE from EbookSources
			where EbookId = ?
		', [$this->EbookId]
		);
	}

	/**
	 * @throws Exceptions\InvalidSourceException
	 */
	private function AddSources(): void{
		foreach($this->Sources as $sortOrder => $source){
			$source->EbookId = $this->EbookId;
			$source->SortOrder = $sortOrder;
			$source->Create();
		}
	}

	private function RemoveContributors(): void{
		Db::Query('
			DELETE from Contributors
			where EbookId = ?
		', [$this->EbookId]
		);
	}

	/**
	 * @throws Exceptions\InvalidContributorException
	 */
	private function AddContributors(): void{
		$allContributors = array_merge($this->Authors, $this->Illustrators, $this->Translators, $this->Contributors);
		foreach($allContributors as $sortOrder => $contributor){
			$contributor->EbookId = $this->EbookId;
			$contributor->SortOrder = $sortOrder;
			$contributor->Create();
		}
	}

	private function RemoveTocEntries(): void{
		Db::Query('
			DELETE from TocEntries
			where EbookId = ?
		', [$this->EbookId]
		);
	}

	private function AddTocEntries(): void{
		if($this->TocEntries !== null){
			foreach($this->TocEntries as $sortOrder => $tocEntry){
				Db::Query('
					INSERT into TocEntries (EbookId, TocEntry, SortOrder)
					values (?,
						?,
						?)
				', [$this->EbookId, $tocEntry, $sortOrder]);
			}
		}
	}

	private function RemoveEbookPlaceholder(): void{
		Db::Query('
			DELETE from EbookPlaceholders
			where EbookId = ?
		', [$this->EbookId]
		);
	}

	/**
	 * @throws Exceptions\InvalidEbookPlaceholderException
	 */
	private function AddEbookPlaceholder(): void{
		if(isset($this->EbookPlaceholder)){
			$this->EbookPlaceholder->EbookId = $this->EbookId;
			$this->EbookPlaceholder->Create();
		}
	}

	// ***********
	// ORM METHODS
	// ***********

	/**
	 * @throws Exceptions\EbookNotFoundException
	 */
	public static function GetByIdentifier(?string $identifier): Ebook{
		if($identifier === null){
			throw new Exceptions\EbookNotFoundException('Invalid identifier: ' . $identifier);
		}

		return Db::Query('
				SELECT *
				from Ebooks
				where Identifier = ?
			', [$identifier], Ebook::class)[0] ?? throw new Exceptions\EbookNotFoundException('Invalid identifier: ' . $identifier);
	}

	/**
	 * @throws Exceptions\EbookNotFoundException
	 */
	public static function GetByIdentifierStartingWith(?string $identifier): Ebook{
		if($identifier === null){
			throw new Exceptions\EbookNotFoundException('Invalid identifier: ' . $identifier);
		}

		return Db::Query('
				SELECT *
				from Ebooks
				where Identifier like concat(?, "%")
			', [$identifier], Ebook::class)[0] ?? throw new Exceptions\EbookNotFoundException('Invalid identifier: ' . $identifier);
	}

	/**
	 * @return array<Ebook>
	 */
	public static function GetAll(): array{
		// Get all ebooks, unsorted.
		return Db::Query('
				SELECT *
				from Ebooks
			', [], Ebook::class);
	}

	/**
	 * @return array<Ebook>
	 */
	public static function GetAllByAuthor(string $urlPath): array{
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
			// Multiple authors, e.g., `karl-marx_friedrich-engels`.
			$authors = explode('_', $urlPath);

			$params = $authors;
			$params[] = sizeof($authors); // The number of authors in the URL must match the number of `Contributor` records.

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
	 * Queries for books in a collection.
	 *
	 * Puts ebooks without a `SequenceNumber` at the end of the list, which is more common in a collection with both published and placeholder ebooks.
	 *
	 * @return array<Ebook>
	 */
	public static function GetAllByCollection(int $collectionId): array{
		$ebooks = Db::Query('
				SELECT e.*
				from Ebooks e
				inner join CollectionEbooks ce using (EbookId)
				where ce.CollectionId = ?
				order by ce.SequenceNumber is null, ce.SequenceNumber, e.EbookCreated desc
				', [$collectionId], Ebook::class);

		return $ebooks;
	}

	/**
	 * Queries for related to books to be shown, e.g., in a carousel.
	 *
	 * Filters out placeholder books because they are not useful for browsing.
	 * @return array<Ebook>
	 */
	public static function GetAllByRelated(Ebook $ebook, int $count, ?EbookTag $relatedTag): array{
		if($relatedTag !== null){
			$relatedEbooks = Db::Query('
						SELECT e.*
						from Ebooks e
						inner join EbookTags et using (EbookId)
						where et.TagId = ?
						    and et.EbookId != ?
						    and e.WwwFilesystemPath is not null
						order by rand()
						limit ?
				', [$relatedTag->TagId, $ebook->EbookId, $count], Ebook::class);
		}
		else{
			$relatedEbooks = Db::Query('
						SELECT *
						from Ebooks
						where EbookId != ?
						    and WwwFilesystemPath is not null
						order by rand()
						limit ?
				', [$ebook->EbookId, $count], Ebook::class);
		}

		return $relatedEbooks;
	}

	/**
	* @param array<string> $tags
	*
	* @return array{ebooks: array<Ebook>, ebooksCount: int}
	*/
	public static function GetAllByFilter(string $query = null, array $tags = [], Enums\EbookSortType $sort = null, int $page = 1, int $perPage = EBOOKS_PER_PAGE, Enums\EbookReleaseStatusFilter $releaseStatusFilter = Enums\EbookReleaseStatusFilter::All): array{
		$limit = $perPage;
		$offset = (($page - 1) * $perPage);
		$joinContributors = '';
		$joinTags = '';
		$params = [];

		switch($releaseStatusFilter){
			case Enums\EbookReleaseStatusFilter::Released:
				$whereCondition = 'where e.WwwFilesystemPath is not null';
				break;
			case Enums\EbookReleaseStatusFilter::Placeholder:
				$whereCondition = 'where e.WwwFilesystemPath is null';
				break;
			case Enums\EbookReleaseStatusFilter::All:
			default:
				if($query !== null && $query != ''){
					// If the query is present, show both released and placeholder ebooks.
					$whereCondition = 'where true';
				}else{
					// If there is no query, hide placeholder ebooks.
					$whereCondition = 'where e.WwwFilesystemPath is not null';
				}
				break;
		}

		$orderBy = 'e.EbookCreated desc';
		if($sort == Enums\EbookSortType::AuthorAlpha){
			$joinContributors = 'inner join Contributors con using (EbookId)';
			$whereCondition .= ' and con.MarcRole = "aut"';
			$orderBy = 'e.WwwFilesystemPath is null, con.SortName, e.EbookCreated desc'; // Put placeholders at the end
		}
		elseif($sort == Enums\EbookSortType::ReadingEase){
			$orderBy = 'e.ReadingEase desc';
		}
		elseif($sort == Enums\EbookSortType::Length){
			$orderBy = 'e.WwwFilesystemPath is null, e.WordCount'; // Put placeholders at the end
		}

		if(sizeof($tags) > 0 && !in_array('all', $tags)){ // 0 tags means "all ebooks"
			$joinTags = 'inner join EbookTags et using (EbookId)
					inner join Tags t using (TagId)';
			$whereCondition .= ' and t.UrlName in ' . Db::CreateSetSql($tags) . ' ';
			$params = $tags;
		}

		if($query !== null && $query != ''){
			$query = trim(preg_replace('|[^a-zA-Z0-9 ]|ius', ' ', Formatter::RemoveDiacritics($query)));
			$query = sprintf('"%s"', $query);  // Require an exact match via double quotes.
			$whereCondition .= ' and match(e.IndexableText) against(? in boolean mode) ';
			$params[] = $query;
		}

		try{
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
		}
		catch(Exceptions\DatabaseQueryException $ex){
			if(stripos($ex->getMessage(), 'General error: 191 Too many words in a FTS phrase or proximity search') !== false){
				// This exception occurs when the search string is too long for MariaDB to handle.
				$ebooksCount = 0;
				$ebooks = [];
			}
			else{
				throw $ex;
			}
		}

		return ['ebooks' => $ebooks, 'ebooksCount' => $ebooksCount];
	}
}
