<?
namespace Enums;

enum DateTimeFormat: string{
	case Sql = 'Y-m-d H:i:s'; // `2022-01-05 23:42:12`
	case Iso = 'Y-m-d\TH:i:s\Z'; // `2022-01-05T23:42:12Z`
	case FullDateTime = 'F j, Y g:i a'; // `January 5, 2022 2:04 pm`
	case Ical = 'Ymd\THis\Z'; //20220105T234212Z
	case Html = 'Y-m-d\TH:i:s';  // `2022-01-05T23:42:12`
	case Rss = 'r'; // `D, d M Y H:i:s`
	case UnixTimestamp = 'U';
}
