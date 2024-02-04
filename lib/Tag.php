<?
/**
 * @property string $Url
 * @property string $UrlName
 */
class Tag extends PropertiesBase{
	public int $TagId;
	public string $Name;
	protected ?string $_UrlName = null;
	protected ?string $_Url = null;
}
