<?

class CoverArt extends PropertiesBase{
	public $Title;
	public $ArtistName;
	public $ArtistSortName;
	public $Year;
	public $ImageUrl;
	public $Status;
	public $AddedDate;

	// In-use cover art has a reference to where it is used
	public $Ebook;

	// Available cover art has fields useful to producers
	public $PDProof;
	public $ImageTags;

	public function __construct(?string $wwwFilesystemPath = null, $ebook = null){
		if($wwwFilesystemPath === null){
			return;
		}

		if(!is_dir($wwwFilesystemPath)){
			throw new Exceptions\InvalidEbookException('Invalid www filesystem path: ' . $wwwFilesystemPath);
		}

		if(!is_file($wwwFilesystemPath . '/content.opf')){
			throw new Exceptions\InvalidEbookException('Invalid content.opf file: ' . $wwwFilesystemPath . '/content.opf');
		}

		if($ebook !== null){
			$this->Ebook = $ebook;
			$this->AddedDate = $ebook->Created;
			$this->ImageUrl = $ebook->CoverImageUrl;
			$this->Status = COVER_ART_STATUS_UNAVAILABLE;
		}

		$rawMetadata = file_get_contents($wwwFilesystemPath . '/content.opf');
		$xml = new SimpleXMLElement(str_replace('xmlns=', 'ns=', $rawMetadata));
		$xml->registerXPathNamespace('dc', 'http://purl.org/dc/elements/1.1/');

		$this->ArtistName = $this->NullIfEmpty($xml->xpath('/package/metadata/dc:contributor[@id="artist"]'));
		$this->ArtistSortName = $this->NullIfEmpty($xml->xpath('/package/metadata/meta[@property="file-as"][@refines="#artist"]'));

		$rawColophon = file_get_contents($wwwFilesystemPath . '/text/colophon.xhtml');
		preg_match('|a painting completed \w+ (\d+)|ius', $rawColophon, $matches);
		if(sizeof($matches) == 2){
			$this->Year = (string)$matches[1];
		}

		$colophonXml = new SimpleXMLElement(str_replace('xmlns=', 'ns=', $rawColophon));
		$this->Title = $this->NullIfEmpty($colophonXml->xpath('/html/body/main/section/p/i[@epub:type="se:name.visual-art.painting"]'));
	}

	public function Contains(string $query): bool{
		$searchString = $this->Title;

		$searchString .= ' ' . $this->ArtistName;

		$searchString .= ' ' . $this->ImageTags;

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

	private function NullIfEmpty($elements): ?string{
		if($elements === false){
			return false;
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
}
