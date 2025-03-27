<?
/**
 * @property-read string $Url
 */
class Tag{
	use Traits\Accessor;

	public int $TagId;
	public string $Name;
	public string $UrlName;
	public Enums\TagType $Type;

	protected string $_Url; // For subclasses.
}
