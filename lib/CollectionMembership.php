<?
use function Safe\preg_replace;

/**
 * @property Collection $Collection
 */
class CollectionMembership{
	use Traits\Accessor;

	public ?int $CollectionEbookId = null;
	public ?int $EbookId = null;
	public ?int $CollectionId = null;
	public ?int $SequenceNumber = null;
	protected ?Collection $_Collection = null;
}
