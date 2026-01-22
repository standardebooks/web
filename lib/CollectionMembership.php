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
	/** If an `Ebook` is listed in a collection under an alternate title, then this value will not be `NULL`.
	 * For example, a collection like the Haycraft-Queen Cornerstones might have an entry that is a short story, but that we have in the corpus as part of a larger omnibus. `$TitleInCollection` can be set to the short story name to show that, intead of the omnibus name, when listing the collection. */
	public ?string $TitleInCollection = null;

	protected Collection $_Collection;
}
