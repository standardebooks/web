<?
use Safe\DateTimeImmutable;
use function Safe\file_get_contents;
use function Safe\filemtime;
use function Safe\preg_match_all;
use function Safe\preg_replace;

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

		// Did we delete or add an ebook?
		try{
			$xml = file_get_contents($path);
			$ebookCount = preg_match_all('/<Product/us', $xml);
			if(sizeof($this->Entries) != $ebookCount){
				return true;
			}
		}
		catch(\Exception){
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

	/**
	 * Load an XML document from disk.
	 *
	 * @throws Exceptions\InvalidFileException If the XML file is missing or invalid XML.
	 */
	protected function LoadXmlDocument(string $path): DOMDocument{
		$dom = new DOMDocument();

		if(!$dom->load($path)){
			throw new Exceptions\InvalidFileException('Couldn\'t load XML document: ' . $path);
		}

		return $dom;
	}

	/**
	 * Return the complete ONIX feed XML.
	 *
	 * @throws Exceptions\InvalidFileException If any of the files in the processing chain is invalid.
	 */
	protected function GetXmlString(): string{
		if(!isset($this->_XmlString)){
			$now = NOW->format(Enums\DateTimeFormat::Ical->value);
			$stylesheet = $this->LoadXmlDocument('/standardebooks.org/tools/se/data/opf2onix.xsl');
			$xsltProcessor = new XSLTProcessor();

			if(!$xsltProcessor->importStylesheet($stylesheet)){
				throw new Exceptions\InvalidFileException('Couldn\'t import ONIX XSLT stylesheet.');
			}

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
					$metadata = $this->LoadXmlDocument($metadataPath);
					$output = $xsltProcessor->transformToXml($metadata);

					if($output === false || $output === null){
						throw new Exceptions\InvalidFileException('Couldn\'t transform OPF metadata to ONIX: ' . $metadataPath);
					}

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
