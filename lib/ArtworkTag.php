<?
use function Safe\preg_match;
use function Safe\preg_replace;

class ArtworkTag extends Tag{
	public function __construct(){
		$this->Type = Enums\TagType::Artwork;
	}


	// *******
	// GETTERS
	// *******

	protected function GetUrl(): string{
		return $this->_Url ??= '/artworks?query=' . Formatter::MakeUrlSafe($this->Name);
	}


	// *******
	// METHODS
	// *******

	/**
	 * @throws Exceptions\InvalidArtworkTagException
	 */
	public function Validate(): void{
		$error = new Exceptions\InvalidArtworkTagException($this->Name);

		if(isset($this->Name)){
			$this->Name = mb_strtolower(trim($this->Name));
			// Collapse spaces into one
			$this->Name = preg_replace('/[\s]+/ius', ' ', $this->Name);

			if(strlen($this->Name) == 0){
				$error->Add(new Exceptions\InvalidArtworkTagNameException());
			}

			if(strlen($this->Name) > ARTWORK_MAX_STRING_LENGTH){
				$error->Add(new Exceptions\StringTooLongException('Artwork tag: '. $this->Name));
			}

			if(preg_match('/[^\sa-z0-9]/ius', $this->Name)){
				$error->Add(new Exceptions\InvalidArtworkTagNameException());
			}

			$this->UrlName = Formatter::MakeUrlSafe($this->Name);
		}
		else{
			$error->Add(new Exceptions\ArtworkTagNameRequiredException());
		}

		if($this->Type != Enums\TagType::Artwork){
			$error->Add(new Exceptions\InvalidArtworkTagTypeException($this->Type));
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	/**
	 * @throws Exceptions\InvalidArtworkTagException
	 */
	public function Create(): void{
		$this->Validate();

		$this->TagId = Db::QueryInt('
			INSERT into Tags (Name, UrlName, Type)
			values (?,
				?,
				?)
			returning
			TagId
		', [$this->Name, $this->UrlName, $this->Type]);
	}

	/**
	 * @throws Exceptions\InvalidArtworkTagException
	 */
	public static function GetOrCreate(ArtworkTag $artworkTag): ArtworkTag{
		$result = Db::Query('
				SELECT *
				from Tags
				where Name = ?
					and Type = ?
			', [$artworkTag->Name, Enums\TagType::Artwork], ArtworkTag::class);

		if(isset($result[0])){
			return $result[0];
		}
		else{
			$artworkTag->Create();
			return $artworkTag;
		}
	}
}
