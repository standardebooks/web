<?
use function Safe\preg_replace;

/**
 * @property string $Url
 */
class Collection{
	use Traits\Accessor;

	public int $CollectionId;
	public string $Name;
	public string $UrlName;
	public ?string $Type = null;
	protected ?string $_Url = null;

	protected function GetUrl(): ?string{
		if($this->_Url === null){
			$this->Url = '/collections/' . $this->UrlName;
		}

		return $this->_Url;
	}

	public static function FromName(string $name): Collection{
		$instance = new Collection();
		$instance->Name = $name;
		$instance->UrlName = Formatter::MakeUrlSafe($instance->Name);
		return $instance;
	}

	public function GetSortedName(): string{
		return preg_replace('/^(the|and|a|)\s/ius', '', $this->Name);
	}

	/**
	 * @throws Exceptions\ValidationException
	 */
	public function Validate(): void{
		$error = new Exceptions\ValidationException();

		if(strlen($this->Name) > EBOOKS_MAX_STRING_LENGTH){
			$error->Add(new Exceptions\StringTooLongException('Collection name: '. $this->Name));
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
			INSERT into Collections (Name, UrlName, Type)
			values (?,
				?,
				?)
		', [$this->Name, $this->UrlName, $this->Type]);
		$this->CollectionId = Db::GetLastInsertedId();
	}

	/**
	 * @throws Exceptions\ValidationException
	 */
	public function GetByUrlNameOrCreate(string $urlName): Collection{
		$result = Db::Query('
				SELECT *
				from Collections
				where UrlName = ?
			', [$urlName], Collection::class);

		if(isset($result[0])){
			return $result[0];
		}
		else{
			$this->Create();
			return $this;
		}
	}

	public function AddEbook(Ebook $ebook, ?int $sequenceNumber): void{
		Db::Query('
			INSERT into CollectionEbooks (EbookId, CollectionId, SequenceNumber)
			values (?,
				?,
				?)
		', [$ebook->EbookId, $this->CollectionId, $sequenceNumber]);
	}

	/**
	 * Removes an ebook from all Collections.
	 */
	public static function RemoveEbook(Ebook $ebook): void{
		Db::Query('
			DELETE from CollectionEbooks
			where
			EbookId = ?
		', [$ebook->EbookId]
		);
	}

}
