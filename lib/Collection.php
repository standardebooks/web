<?
use function Safe\preg_replace;

/**
 * @property string $Url
 */
class Collection{
	use Traits\Accessor;

	public string $Name;
	public string $UrlName;
	public ?int $SequenceNumber = null;
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
}
