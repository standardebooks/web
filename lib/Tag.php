<?
/**
 * @property string $Url
 */
class Tag extends Accessor{
	public int $TagId;
	public string $Name;
	public string $UrlName;
	protected ?string $_Url = null;
}
