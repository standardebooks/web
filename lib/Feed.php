<?
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

	public function __construct(string $title, string $url, string $path, array $entries){
		$this->Url = $url;
		$this->Title = $title;
		$this->Path = $path;
		$this->Entries = $entries;
	}

	protected function CleanXmlString(string $xmlString): string{
		$tempFilename = tempnam('/tmp/', 'se-');
		file_put_contents($tempFilename, $xmlString);
		exec('se clean ' . escapeshellarg($tempFilename) . ' 2>&1', $output); // Capture the result in case there's an error, otherwise it prints to stdout
		$output = file_get_contents($tempFilename);
		unlink($tempFilename);

		if($this->Stylesheet !== null){
			$output = str_replace("<?xml version=\"1.0\" encoding=\"utf-8\"?>", "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<?xml-stylesheet href=\"" . $this->Stylesheet . "\" type=\"text/xsl\"?>", $output);
		}

		return $output;
	}

	protected function GetXmlString(): string{
		// Virtual function, meant to be implemented by subclass
		return '';
	}

	public function SaveIfChanged(): void{
		// Did we actually update the feed? If so, write to file and update the index
		if($this->HasChanged($this->Path)){
			// Files don't match, save the file
			$this->Updated = new DateTime();
			$this->Save();
		}
	}

	public function Save(): void{
		$feed = $this->GetXmlString();

		file_put_contents($this->Path, $feed);
	}
}
