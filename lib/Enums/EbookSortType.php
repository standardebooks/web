<?
namespace Enums;

enum EbookSortType: string{
	case Newest = 'newest';
	case AuthorAlpha = 'author-alpha';
	case ReadingEase = 'reading-ease';
	case Length = 'length';
	case Relevance = 'relevance';
	case Popularity = 'popularity';
	case Default = 'default'; // Interpreted as `Relevance` if a query is present, `Newest` if not.
}
