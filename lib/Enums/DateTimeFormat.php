<?
namespace Enums;

enum DateTimeFormat: string{
	/** Like `2022-01-05 23:42:12`. */
	case Sql = 'Y-m-d H:i:s';

	/** Like `2022-01-05T23:42:12Z`. */
	case Iso = 'Y-m-d\TH:i:s\Z';

	/** Like `January 5, 2022 2:04 pm`. */
	case FullDateTime = 'F j, Y g:i a';

	/** Like `20220105T234212Z`. */
	case Ical = 'Ymd\THis\Z';

	/** Like `2022-01-05T23:42:12`. */
	case Html = 'Y-m-d\TH:i:s';

	/** Like `Sat, 5 Jan 2022 23:42:12 +0000`. */
	case Rss = 'r';

	/** Like `1641426132`. */
	case UnixTimestamp = 'U';

	/** Like Jan 5, 2024 */
	case ShortDate = 'M j, Y';
}
