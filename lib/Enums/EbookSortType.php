<?
namespace Enums;

enum EbookSortType: string{
	case Newest = 'newest';
	case AuthorAlpha = 'author-alpha';
	case ReadingEase = 'reading-ease';
	case Length = 'length';
	case Relevance = 'relevance';
	case Popularity = 'popularity';
	/** Interpreted as `Relevance` if a query is present, `Newest` if not. */
	case Default = 'default';
}
