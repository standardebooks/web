<?
use Safe\DateTimeImmutable;
use function Safe\filesize;
use function Safe\file_put_contents;
use function Safe\json_encode;
use function Safe\preg_replace;

abstract class OpdsFeed extends AtomFeed{
	public ?OpdsNavigationFeed $Parent = null;
	protected string $_JsonString;

	/**
	 * @param string $title
	 * @param string $subtitle
	 * @param string $url
	 * @param string $path
	 * @param array<Ebook|OpdsNavigationEntry> $entries
	 * @param OpdsNavigationFeed $parent
	 */
	public function __construct(string $title, string $subtitle, string $url, string $path, array $entries, ?OpdsNavigationFeed $parent){
		parent::__construct($title, $subtitle, $url, $path, $entries);
		$this->Parent = $parent;
		$this->Stylesheet = SITE_URL . '/feeds/opds/style';
	}


	// *******
	// METHODS
	// *******

	/**
	 * Get the path to the target feed, given the requested MIME type.
	 */
	public static function GetPath(string $path, string $mimeType): string{
		// Remove file extension first.
		$path = preg_replace('/\.[a-z]+$/iu', '', $path);
		if($mimeType == 'application/opds+json; charset=utf-8'){
			$path .= '.json';
		}
		else{
			$path .= '.xml';
		}

		return $path;
	}

	/**
	 * Return the JSON path that corresponds to an OPDS XML path.
	 */
	protected function GetJsonPath(string $xmlPath): string{
		return preg_replace('/\.xml$/iu', '.json', $xmlPath);
	}

	protected function SaveUpdated(string $entryId, DateTimeImmutable $updated): void{
		// Only save the updated timestamp for the given entry ID in this file.
		foreach($this->Entries as $entry){
			if($entry instanceof OpdsNavigationEntry){
				if($entry->Id == SITE_URL . $entryId){
					$entry->Updated = $updated;
				}
			}
		}

		$this->Updated = $updated;

		unset($this->_XmlString);
		unset($this->_JsonString);
		file_put_contents($this->Path, $this->GetXmlString());
		file_put_contents($this->GetJsonPath($this->Path), $this->GetJsonString());

		// Do we have any parents of our own to update?
		if($this->Parent !== null){
			$this->Parent->SaveUpdated($this->Id, $updated);
		}
	}

	/**
	 * Return the OPDS 2.0 JSON string for this feed.
	 */
	protected function GetJsonString(): string{
		return $this->_JsonString ??= json_encode($this->GetJsonObject(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n";
	}

	/**
	 * Return the OPDS 2.0 JSON string for this feed.
	 */
	public function ToJsonString(): string{
		return $this->GetJsonString();
	}

	/**
	 * Return the base OPDS 2.0 feed object.
	 *
	 * @return array<string, mixed>
	 */
	protected function GetJsonObject(): array{
		$output = [
			'metadata' => [
				'title' => $this->Title,
				'modified' => $this->Updated->format(Enums\DateTimeFormat::Iso->value),
			],
			'links' => [
				$this->GenerateOpds2Link(SITE_URL . $this->Url, ['self'], 'application/opds+json'),
				$this->GenerateOpds2Link(SITE_URL . '/feeds/opds', ['start'], 'application/opds+json'),
				$this->GenerateOpds2Link(SITE_URL . '/feeds/opds/all{?query,per-page,page}', ['search'], 'application/opds+json', 'Search Standard Ebooks', null, true),
				$this->GenerateOpds2Link(SITE_URL . '/feeds/opds/all', ['http://opds-spec.org/crawlable'], 'application/opds+json'),
			],
		];

		if($this->Subtitle !== null){
			$output['metadata']['subtitle'] = $this->Subtitle;
		}

		if($this->Parent !== null){
			$output['links'][] = $this->GenerateOpds2Link(SITE_URL . $this->Parent->Url, ['up'], 'application/opds+json');
		}

		return $output;
	}

	/**
	 * Return an OPDS 2.0 link object.
	 *
	 * @param array<string> $rel
	 *
	 * @return array<string, mixed>
	 */
	protected function GenerateOpds2Link(string $href, array $rel, ?string $type = null, ?string $title = null, ?int $size = null, bool $isTemplated = false): array{
		$link = [
			'href' => $href,
			'rel' => $rel,
		];

		if($type !== null){
			$link['type'] = $type;
		}

		if($title !== null){
			$link['title'] = $title;
		}

		if($size !== null){
			$link['size'] = $size;
		}

		if($isTemplated){
			$link['templated'] = true;
		}

		return $link;
	}

	/**
	 * Return an OPDS 2.0 contributor object.
	 *
	 * @return array<string, mixed>
	 */
	protected function GenerateOpds2Contributor(Contributor $contributor, ?string $url = null): array{
		$output = [
			'name' => $contributor->Name,
		];

		if($contributor->SortName !== null){
			$output['sortAs'] = $contributor->SortName;
		}

		if($url !== null){
			$output['links'] = [
				$this->GenerateOpds2Link(SITE_URL . $url, ['alternate'], 'text/html'),
			];
		}

		return $output;
	}

	/**
	 * Return an OPDS 2.0 publication object for an ebook.
	 *
	 * @return array<string, mixed>
	 */
	protected function GenerateOpds2Publication(Ebook $ebook): array{
		$output = [
			'metadata' => [
				'@type' => 'http://schema.org/EBook',
				'identifier' => $ebook->Identifier,
				'title' => $ebook->Title,
				'author' => [],
				'language' => $ebook->Language,
				'publisher' => 'Standard Ebooks',
				'description' => $ebook->Description,
				'belongsTo' => [
					'subjects' => [],
				],
			],
			'images' => [
				$this->GenerateOpds2Link(SITE_URL . $ebook->Url . '/downloads/cover.jpg', ['http://opds-spec.org/image'], 'image/jpeg'),
				$this->GenerateOpds2Link(SITE_URL . $ebook->Url . '/downloads/cover-thumbnail.jpg', ['http://opds-spec.org/image/thumbnail'], 'image/jpeg'),
			],
			'links' => [
				$this->GenerateOpds2Link(SITE_URL . $ebook->Url, ['alternate'], 'text/html', 'This ebook’s page at Standard Ebooks'),
			],
		];

		if($ebook->EbookCreated !== null){
			$output['metadata']['published'] = $ebook->EbookCreated->format(Enums\DateTimeFormat::Iso->value);
		}

		if($ebook->EbookUpdated !== null){
			$output['metadata']['modified'] = $ebook->EbookUpdated->format(Enums\DateTimeFormat::Iso->value);
		}

		foreach($ebook->Authors as $author){
			$output['metadata']['author'][] = $this->GenerateOpds2Contributor($author, $ebook->AuthorsUrl);
		}

		foreach($ebook->LocSubjects as $subject){
			$output['metadata']['belongsTo']['subjects'][] = [
				'name' => $subject->Name,
				'scheme' => 'http://purl.org/dc/terms/LCSH',
			];
		}

		foreach($ebook->Tags as $subject){
			$output['metadata']['belongsTo']['subjects'][] = [
				'name' => $subject->Name,
				'scheme' => 'https://standardebooks.org/vocab/subjects',
			];
		}

		if($ebook->EpubUrl !== null){
			$output['links'][] = $this->GenerateOpds2Link(SITE_URL . $ebook->GetDownloadUrl(Enums\EbookFormatType::Epub, Enums\EbookDownloadSource::Feed), ['http://opds-spec.org/acquisition/open-access'], 'application/epub+zip', 'Recommended compatible epub', file_exists(WEB_ROOT . $ebook->EpubUrl) ? filesize(WEB_ROOT . $ebook->EpubUrl) : null);
		}

		if($ebook->AdvancedEpubUrl !== null){
			$output['links'][] = $this->GenerateOpds2Link(SITE_URL . $ebook->GetDownloadUrl(Enums\EbookFormatType::AdvancedEpub, Enums\EbookDownloadSource::Feed), ['http://opds-spec.org/acquisition/open-access'], 'application/epub+zip', 'Advanced epub', file_exists(WEB_ROOT . $ebook->AdvancedEpubUrl) ? filesize(WEB_ROOT . $ebook->AdvancedEpubUrl) : null);
		}

		if($ebook->KepubUrl !== null){
			$output['links'][] = $this->GenerateOpds2Link(SITE_URL . $ebook->GetDownloadUrl(Enums\EbookFormatType::Kepub, Enums\EbookDownloadSource::Feed), ['http://opds-spec.org/acquisition/open-access'], 'application/kepub+zip', 'Kobo Kepub epub', file_exists(WEB_ROOT . $ebook->KepubUrl) ? filesize(WEB_ROOT . $ebook->KepubUrl) : null);
		}

		if($ebook->Azw3Url !== null){
			$output['links'][] = $this->GenerateOpds2Link(SITE_URL . $ebook->GetDownloadUrl(Enums\EbookFormatType::Azw3, Enums\EbookDownloadSource::Feed), ['http://opds-spec.org/acquisition/open-access'], 'application/x-mobipocket-ebook', 'Amazon Kindle azw3', file_exists(WEB_ROOT . $ebook->Azw3Url) ? filesize(WEB_ROOT . $ebook->Azw3Url) : null);
		}

		if(file_exists(WEB_ROOT . $ebook->TextSinglePageUrl . '.xhtml')){
			$output['links'][] = $this->GenerateOpds2Link(SITE_URL . $ebook->TextSinglePageUrl, ['http://opds-spec.org/acquisition/open-access'], 'application/xhtml+xml', 'XHTML', filesize(WEB_ROOT . $ebook->TextSinglePageUrl . '.xhtml'));
		}

		return $output;
	}

	public function SaveIfChanged(): bool{
		// Did we actually update the feed? If so, write to file and update the index.
		if($this->HasChanged($this->Path) || !is_file($this->GetJsonPath($this->Path))){
			// Files don't match, save the file and update the parent navigation feed with the last updated timestamp.

			$this->Updated = NOW;

			if($this->Parent !== null){
				$this->Parent->SaveUpdated($this->Id, $this->Updated);
			}

			// Save our own file.
			$this->Save();
			return true;
		}

		return false;
	}

	public function Save(): void{
		parent::Save();
		file_put_contents($this->GetJsonPath($this->Path), $this->GetJsonString());
	}
}
