<?
use Safe\DateTimeImmutable;
use function Safe\filemtime;
use function Safe\preg_replace;
use function Safe\shell_exec;

/**
 * At this time, an ONIX feed is a single feed of all ebooks, sorted by most-recently-updated first.
 */
class OnixFeed extends Feed{
	/** @var array<Ebook> */
	public $Entries = [];

	public function __construct(){
		$this->Path = WEB_ROOT . '/feeds/onix/all.xml';
		$this->Entries = Db::Query('SELECT * from Ebooks where WwwFilesystemPath is not null order by EbookUpdated desc', [], Ebook::class);
	}

	protected function HasChanged(string $path): bool{
		if(!is_file($path) || sizeof($this->Entries) == 0){
			return true;
		}

		$latestUpdatedEbook = $this->Entries[0]->EbookUpdated;

		try{
			$modTime = new DateTimeImmutable('@' . filemtime($path));
		}
		catch(\Exception){
			return true;
		}

		if($latestUpdatedEbook > $modTime){
			return true;
		}

		return false;
	}

	public function SaveIfChanged(): bool
	{
		// Did we actually update the feed? If so, write to file and update the index.
		if($this->HasChanged($this->Path)){
			// Files don't match, save the file and update the parent navigation feed with the last updated timestamp.

			$this->Updated = NOW;

			$this->Save();
			return true;
		}

		return false;
	}

	protected function GetXmlString(): string{
		if(!isset($this->_XmlString)){
			$now = NOW->format(Enums\DateTimeFormat::Ical->value);

			$feed = <<<TEXT
			<?xml version="1.0" encoding="utf-8"?>
			<ONIXMessage xmlns="http://ns.editeur.org/onix/3.1/reference" release="3.1">
				<Header>
					<Sender>
						<SenderName>Standard Ebooks</SenderName>
					</Sender>
					<SentDateTime>$now</SentDateTime>
				</Header>
			TEXT;

			foreach($this->Entries as $ebook){
				$metadataPath = $ebook->WwwFilesystemPath . '/content.opf';
				if(is_file($metadataPath)){
					$output = shell_exec('xsltproc /standardebooks.org/tools/se/data/opf2onix.xsl ' . escapeshellarg($metadataPath)) ?? '';

					$output = preg_replace('/<\?xml version="1\.0" encoding="utf-8"\?>\s*<\/?ONIXMessage[^>]*?>\s*<Header[^>]*?>.+?<\/Header>/ius', '', $output);
					$output = preg_replace('/<\/ONIXMessage>/ius', '', $output);

					$feed .= $output . "\n";
				}
			}

			$feed .= '</ONIXMessage>';

			$this->_XmlString = $this->CleanXmlString($feed);
		}

		return $this->_XmlString;
	}
}
