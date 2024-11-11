<?
class EbookTag extends Tag{
	public function __construct(){
		$this->Type = Enums\TagType::Ebook;
	}


	// *******
	// GETTERS
	// *******

	protected function GetUrl(): string{
		if(!isset($this->_Url)){
			$this->_Url = '/subjects/' . $this->UrlName;
		}

		return $this->_Url;
	}


	// *******
	// METHODS
	// *******

	/**
	 * @throws Exceptions\ValidationException
	 */
	public function Validate(): void{
		$error = new Exceptions\ValidationException();

		if(isset($this->Name)){
			$this->Name = trim($this->Name);

			if($this->Name == ''){
				$error->Add(new Exceptions\EbookTagNameRequiredException());
			}

			if(strlen($this->Name) > EBOOKS_MAX_STRING_LENGTH){
				$error->Add(new Exceptions\StringTooLongException('Ebook tag: '. $this->Name));
			}

			$this->UrlName = Formatter::MakeUrlSafe($this->Name);
		}
		else{
			$error->Add(new Exceptions\EbookTagNameRequiredException());
		}

		if($this->Type != Enums\TagType::Ebook){
			$error->Add(new Exceptions\InvalidEbookTagTypeException($this->Type));
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	/**
	 * @throws Exceptions\ValidationException
	 */
	public function Create(): void{
		$this->Validate();

		Db::Query('
			INSERT into Tags (Name, UrlName, Type)
			values (?,
				?,
				?)
		', [$this->Name, $this->UrlName, $this->Type]);
		$this->TagId = Db::GetLastInsertedId();
	}


	// ***********
	// ORM METHODS
	// ***********

	/**
	 * @throws Exceptions\ValidationException
	 */
	public function GetByNameOrCreate(string $name): EbookTag{
		$result = Db::Query('
				SELECT *
				from Tags
				where Name = ?
					and Type = ?
			', [$name, Enums\TagType::Ebook], EbookTag::class);

		if(isset($result[0])){
			return $result[0];
		}
		else{
			$this->Create();
			return $this;
		}
	}

	/**
	 * @return array<EbookTag>
	 */
	public static function GetAll(): array{
		$tags = Db::Query('
				SELECT *
				from Tags t
				where Type = ?
				order by Name
			', [Enums\TagType::Ebook], EbookTag::class);

		return $tags;
	}
}
