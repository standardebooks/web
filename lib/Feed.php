<?
use Safe\DateTimeImmutable;

use function Safe\exec;
use function Safe\file_get_contents;
use function Safe\file_put_contents;
use function Safe\glob;
use function Safe\tempnam;
use function Safe\unlink;

abstract class Feed{
	public string $Url;
	public string $Title;
	/** @var array<Ebook|OpdsNavigationEntry> $Entries */
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
		exec('se clean ' . escapeshellarg($tempFilename) . ' 2>&1', $output); // Capture the result in case there's an error, otherwise it prints to stdout
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
	public static function RebuildFeedsCache(?Enums\FeedType $returnType = null, ?Enums\FeedCollectionType $returnCollectionType = null): ?array{
		$retval = null;
		$collator = Collator::create('en_US'); // Used for sorting letters with diacritics like in author names
		if($collator === null){
			throw new Exceptions\AppException('Couldn\'t create collator object when rebuilding feeds cache.');
		}

		foreach(Enums\FeedType::cases() as $type){
			foreach(Enums\FeedCollectionType::cases() as $collectionType){
				$files = glob(WEB_ROOT . '/feeds/' . $type->value . '/' . $collectionType->value . '/*.xml');

				$feeds = [];

				foreach($files as $file){
					$obj = new stdClass();
					$obj->Url = '/feeds/' . $type->value . '/' . $collectionType->value . '/' . basename($file, '.xml');

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
}
