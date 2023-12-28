<?
use Safe\DateTime;
use function Safe\exec;
use function Safe\file_get_contents;
use function Safe\file_put_contents;
use function Safe\tempnam;
use function Safe\unlink;

class Feed{
	public $Url;
	public $Title;
	public $Entries = [];
	public $Path = null;
	public $Stylesheet = null;
	protected $XmlString = null;

	/**
	 * @param string $title
	 * @param string $url
	 * @param string $path
	 * @param array<Ebook> $entries
	 */
	public function __construct(string $title, string $url, string $path, array $entries){
		$this->Url = $url;
		$this->Title = $title;
		$this->Path = $path;
		$this->Entries = $entries;
	}


	// *******
	// METHODS
	// *******

	protected function CleanXmlString(string $xmlString): string{
		$tempFilename = tempnam('/tmp/', 'se-');
		file_put_contents($tempFilename, $xmlString);
		exec('se clean ' . escapeshellarg($tempFilename) . ' 2>&1', $output); // Capture the result in case there's an error, otherwise it prints to stdout
		$output = file_get_contents($tempFilename);
		unlink($tempFilename);

		// At the moment, `se clean` strips stylesheet declarations. Restore them here.
		if($this->Stylesheet !== null){
			$output = str_replace("<?xml version=\"1.0\" encoding=\"utf-8\"?>", "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<?xml-stylesheet href=\"" . $this->Stylesheet . "\" type=\"text/xsl\"?>", $output);
		}

		return $output;
	}

	protected function GetXmlString(): string{
		// Virtual function, meant to be implemented by subclass
		return '';
	}

	public function Save(): void{
		$feed = $this->GetXmlString();

		file_put_contents($this->Path, $feed);
	}
}
