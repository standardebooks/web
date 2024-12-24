<?
use function Safe\preg_replace;

/**
 * @property string $Url
 * @property array<Ebook> $Ebooks
 */
class Collection{
	use Traits\Accessor;

	public int $CollectionId;
	public string $Name;
	public string $UrlName;
	public ?Enums\CollectionType $Type = null;
	public bool $ArePlaceholdersComplete; /** Has a producer verified that every possible item in this `Collection` been added to our database? */

	protected string $_Url;
	/** @var array<Ebook> $_Ebooks */
	protected array $_Ebooks;


	// *******
	// GETTERS
	// *******

	protected function GetUrl(): string{
		if(!isset($this->_Url)){
			$this->_Url = '/collections/' . $this->UrlName;
		}

		return $this->_Url;
	}

	/**
	 * @return array<Ebook>
	 */
	protected function GetEbooks(): array{
		if(!isset($this->_Ebooks)){
			$this->_Ebooks = Ebook::GetAllByCollection($this->CollectionId);
		}

		return $this->_Ebooks;
	}


	// ***********
	// ORM METHODS
	// ***********

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

	/**
	 * @throws Exceptions\CollectionNotFoundException
	 */
	public static function GetByUrlName(?string $urlName): Collection{
		if($urlName === null){
			throw new Exceptions\CollectionNotFoundException();
		}

		$result = Db::Query('
				SELECT *
				from Collections
				where UrlName = ?
			', [$urlName], Collection::class);

		return $result[0] ?? throw new Exceptions\CollectionNotFoundException();;
	}

	/**
	 * @return array<Collection>
	 */
	public static function GetAll(): array{
		return Db::Query('
					SELECT *
					from Collections
					order by Name asc
				', [], Collection::class);
	}

	/**
	 * Deletes `Collection`s that no `Ebook`s are members of.
	 */
	public static function DeleteUnused(): void{
		Db::Query('
			DELETE
			from Collections
			where CollectionId not in
				(select distinct CollectionId from CollectionEbooks)
		');
	}

	// *******
	// METHODS
	// *******

	public function GetSortedName(): string{
		return preg_replace('/^(the|and|a|)\s/ius', '', $this->Name);
	}

	/**
	 * @throws Exceptions\InvalidCollectionException
	 */
	public function Validate(): void{
		$error = new Exceptions\InvalidCollectionException();

		if(isset($this->Name)){
			$this->Name = trim($this->Name);

			if($this->Name == ''){
				$error->Add(new Exceptions\CollectionNameRequiredException());
			}

			if(strlen($this->Name) > EBOOKS_MAX_STRING_LENGTH){
				$error->Add(new Exceptions\StringTooLongException('Collection name: '. $this->Name));
			}
		}
		else{
			$error->Add(new Exceptions\CollectionNameRequiredException());
		}

		if(isset($this->UrlName)){
			$this->UrlName = trim($this->UrlName);

			if($this->UrlName == ''){
				$error->Add(new Exceptions\CollectionUrlNameRequiredException());
			}

			if(strlen($this->UrlName) > EBOOKS_MAX_STRING_LENGTH){
				$error->Add(new Exceptions\StringTooLongException('Collection UrlName: '. $this->UrlName));
			}
		}
		else{
			$error->Add(new Exceptions\CollectionUrlNameRequiredException());
		}

		if($this->Type !== null && ($this->Type != Enums\CollectionType::Series && $this->Type != Enums\CollectionType::Set)){
			$error->Add(new Exceptions\InvalidCollectionTypeException($this->Type));
		}

		if($error->HasExceptions){
			throw $error;
		}
	}

	/**
	 * @throws Exceptions\InvalidCollectionException
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
	 * @throws Exceptions\InvalidCollectionException
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
