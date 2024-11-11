<?
/**
 * @property Collection $Collection
 */
class CollectionMembership{
	use Traits\Accessor;

	public int $EbookId;
	public int $CollectionId;
	public ?int $SequenceNumber = null;
	public int $SortOrder;

	protected Collection $_Collection;
}
