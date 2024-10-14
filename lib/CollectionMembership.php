<?
/**
 * @property Collection $Collection
 */
class CollectionMembership{
	use Traits\Accessor;

	public ?int $EbookId = null;
	public ?int $CollectionId = null;
	public ?int $SequenceNumber = null;
	public ?int $SortOrder = null;
	protected ?Collection $_Collection = null;
}
