<?
/**
 * Note that in very rare cases, an `Ebook` may be part of the same collection several times, under alternate titles. For example, <https://standardebooks.org/ebooks/charles-augustin-sainte-beuve/essays/elizabeth-lee> is part of the set `Encyclopædia Britannica’s Gateway to the Great Books` as both `What is a Classic?` and also `Montaigne`. That `Ebook` would have the collection metadata listed twice in its `content.opf` file and it would have two entries in the `CollectionMembership` table.
 *
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
