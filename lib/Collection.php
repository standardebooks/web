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

	/**
	 * @throws Exceptions\CollectionNotFoundException
	 */
	public static function Get(?int $collectionId): Collection{
		if($collectionId === null){
			throw new Exceptions\CollectionNotFoundException();
		}

		$result = Db::Query('
				SELECT *
				from Collections
				where CollectionId = ?
			', [$collectionId], Collection::class);

		return $result[0] ?? throw new Exceptions\CollectionNotFoundException();;
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

		if($this->Type !== null && ($this->Type != 'series' && $this->Type != 'set')){
			$error->Add(new Exceptions\InvalidCollectionTypeException($this->Type));
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
}
