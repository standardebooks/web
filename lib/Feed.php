<?
use Safe\DateTimeImmutable;

use function Safe\exec;
use function Safe\file_get_contents;
use function Safe\file_put_contents;
use function Safe\glob;
use function Safe\preg_match;
use function Safe\tempnam;
use function Safe\unlink;

abstract class Feed{
	public string $Url;
	public string $Title;
	/** @var array<Ebook|OpdsNavigationEntry> */
	public $Entries = [];
	public string $Path;
	public ?string $Stylesheet = null;
	protected string $_XmlString;
	public DateTimeImmutable $Updated;

	/**
	 * @param string $title
	 * @param string $url
	 * @param string $path
	 * @param array<Ebook|OpdsNavigationEntry> $entries
	 */
	public function __construct(string $title, string $url, string $path, array $entries){
		$this->Url = $url;
		$this->Title = $title;
		$this->Path = $path;
		$this->Entries = $entries;
	}


	// *******
	// GETTERS
	// *******

	abstract protected function GetXmlString(): string;


	// *******
	// METHODS
	// *******

	abstract public function SaveIfChanged(): bool;

	protected function CleanXmlString(string $xmlString): string{
		$tempFilename = tempnam('/tmp/', 'se-');
		file_put_contents($tempFilename, $xmlString);
		exec('se clean ' . escapeshellarg($tempFilename) . ' 2>&1', $output); // Capture the result in case there's an error, otherwise it prints to `stdout`.
		$output = file_get_contents($tempFilename);
		@unlink($tempFilename);

		// At the moment, `se clean` strips stylesheet declarations. Restore them here.
		if($this->Stylesheet !== null){
			$output = str_replace("<?xml version=\"1.0\" encoding=\"utf-8\"?>", "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<?xml-stylesheet href=\"" . $this->Stylesheet . "\" type=\"text/xsl\"?>", $output);
		}

		return $output;
	}

	public function Save(): void{
		$feed = $this->GetXmlString();

		file_put_contents($this->Path, $feed);
	}

	/**
	 * @return ?array<stdClass>
	 *
	 * @throws Exceptions\AppException
	 */
	public static function RebuildFeedsCache(?Enums\FeedFormatType $returnType = null, ?Enums\FeedCollectionType $returnCollectionType = null): ?array{
		$retval = null;
		$collator = Collator::create('en_US'); // Used for sorting letters with diacritics like in author names.
		if($collator === null){
			throw new Exceptions\AppException('Couldn\'t create collator object when rebuilding feeds cache.');
		}

		foreach(Enums\FeedFormatType::cases() as $type){
			foreach(Enums\FeedCollectionType::cases() as $collectionType){
				$files = glob(WEB_ROOT . '/feeds/' . $type->value . '/' . $collectionType->value . '/*.xml');

				$feeds = [];

				foreach($files as $file){
					$obj = new stdClass();
					$obj->Label = null;
					$obj->LabelSort = null;
					$obj->Url = '/feeds/' . $type->value . '/' . $collectionType->value . '/' . basename($file, '.xml');
					$output = [];

					exec('attr -q -g se-label ' . escapeshellarg($file) . ' 2>&1', $output, $returnCode);
					if($returnCode == 0 && sizeof($output ?? []) > 0){
						$obj->Label = $output[0];
					}
					if($obj->Label == null){
						$obj->Label = basename($file, '.xml');
					}

					// `exec()` *appends* to the array, so we must reset it before continuing.
					$output = [];
					exec('attr -q -g se-label-sort ' . escapeshellarg($file) . ' 2>&1', $output, $returnCode);
					if($returnCode == 0 && sizeof($output ?? []) > 0){
						$obj->LabelSort = $output[0];
					}
					if($obj->LabelSort == null){
						$obj->LabelSort = basename($file, '.xml');
					}

					$feeds[] = $obj;
				}

				usort($feeds, function(stdClass $a, stdClass $b) use($collator): int{
					$result = $collator->compare($a->LabelSort, $b->LabelSort);
					return $result === false ? 0 : $result;
				});

				if($type == $returnType && $collectionType == $returnCollectionType){
					$retval = $feeds;
				}

				apcu_store('feeds-index-' . $type->value . '-' . $collectionType->value, $feeds);
			}
		}

		return $retval;
	}

	/**
	 * Decide what content-type to serve for a feed via HTTP content negotation.
	 *
	 * If the feed is viewed from a web browser, we will usually serve `application/xml` as that's typically what's in the browser's `Accept` header.
	 * If the `Accept` header is `application/rss+xml` or `application/atom+xml` then serve that instead, as those are the "technically correct" content types that may be requested by RSS readers.
	 *
	 * @param string $relativePath The relative path to the file we're serving, e.g. `/feeds/opds/all.xml`.
	 *
	 * @return string The MIME type to serve.
	 */
	public static function NegotiateMimeType(string $relativePath): string{
		$http = new HTTP2();
		$mime = 'application/xml';

		if(preg_match('/^\/feeds\/opds/', $relativePath)){
			$contentType = [
				'application/atom+xml',
				'application/xml',
				'text/xml',
				'application/opds+json',
				'application/json',
				'application/javascript'
			];
			$mime = $http->negotiateMimeType($contentType, 'application/atom+xml');

			if($mime == 'application/json' || $mime == 'application/opds+json' || $mime == 'application/javascript'){
				return 'application/opds+json; charset=utf-8';
			}

			if($mime == 'application/atom+xml'){
				if(preg_match('/\/index\.xml$/', $relativePath)){
					$mime .= ';profile=opds-catalog;kind=navigation; charset=utf-8';
				}
				else{
					$mime .= ';profile=opds-catalog;kind=acquisition; charset=utf-8';
				}
			}
		}
		elseif(preg_match('/^\/feeds\/rss/', $relativePath)){
			$contentType = [
				'application/rss+xml',
				'application/xml',
				'text/xml'
			];
			$mime = $http->negotiateMimeType($contentType, 'application/rss+xml');
		}
		elseif(preg_match('/^\/feeds\/atom/', $relativePath)){
			$contentType = [
				'application/atom+xml',
				'application/xml',
				'text/xml'
			];
			$mime = $http->negotiateMimeType($contentType, 'application/atom+xml');
		}
		elseif(preg_match('/^\/feeds\/onix/', $relativePath)){
			$contentType = [
				'application/onix+xml',
				'application/xml',
				'text/xml'
			];
			$mime = $http->negotiateMimeType($contentType, 'application/onix+xml');
		}

		return $mime;
	}
}
