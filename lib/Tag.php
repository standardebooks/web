<?
/**
 * @property string $Url
 * @property string $UrlName
 */
class Tag{
	use Traits\Accessor;

	public int $TagId;
	public string $Name;
	public TagType $Type;
	protected ?string $_UrlName = null;
	protected ?string $_Url = null;
}
