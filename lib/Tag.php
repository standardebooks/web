<?
/**
 * @property string $Url
 */
class Tag{
	use Traits\Accessor;

	public int $TagId;
	public string $Name;
	public string $UrlName;
	protected ?string $_Url = null;
}
