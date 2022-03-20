<?
use function Safe\preg_replace;

class Formatter{
	public static function RemoveDiacritics(string $text): string{
		if(!isset($GLOBALS['transliterator'])){
			$GLOBALS['transliterator'] = Transliterator::createFromRules(':: Any-Latin; :: Latin-ASCII; :: NFD; :: [:Nonspacing Mark:] Remove; :: Lower(); :: NFC;', Transliterator::FORWARD);
		}

		return $GLOBALS['transliterator']->transliterate($text);
	}

	public static function MakeUrlSafe(string $text): string{
		// Remove accent characters
		$text = self::RemoveDiacritics($text);

		// Remove apostrophes
		$text = str_replace('\'', '', $text);

		// Trim and convert to lowercase
		$text = mb_strtolower(trim($text));

		// Then convert any non-digit, non-letter character to a space
		$text = preg_replace('/[^0-9a-zA-Z]/ius', ' ', $text) ?: '';

		// Then convert any instance of one or more space to dash
		$text = preg_replace('/\s+/ius', '-', $text) ?: '';

		// Finally, trim dashes
		$text = trim($text, '-');

		return $text;
	}

	public static function ToPlainText(?string $text): string{
		return htmlspecialchars(trim($text), ENT_QUOTES, 'UTF-8');
	}
}
