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
		$text = preg_replace('/[\'’]/u', '', $text);

		// Trim and convert to lowercase
		$text = mb_strtolower(trim($text));

		// Then convert any non-digit, non-letter character to a space
		$text = preg_replace('/[^0-9a-zA-Z]/ius', ' ', $text);

		// Then convert any instance of one or more space to dash
		$text = preg_replace('/\s+/ius', '-', $text);

		// Finally, trim dashes
		$text = trim($text, '-');

		return $text;
	}

	public static function ToPlainText(?string $text): string{
		return htmlspecialchars(trim($text ?? ''), ENT_QUOTES, 'utf-8');
	}

	public static function ToPlainXmlText(?string $text): string{
		return htmlspecialchars(trim($text ?? ''), ENT_QUOTES|ENT_XML1, 'utf-8');
	}

	public static function ToFileSize(?int $bytes): string{
		// See https://stackoverflow.com/a/5501447
		$output = '';

		if($bytes >= 1073741824){
			$output = number_format(round($bytes / 1073741824, 1), 1) . 'G';
		}
		elseif($bytes >= 1048576){
			$output = number_format(round($bytes / 1048576, 1), 1) . 'M';
		}
		elseif($bytes >= 1024){
			$output = number_format($bytes / 1024, 2) . 'KB';
		}
		elseif($bytes > 1){
			$output = $bytes . ' bytes';
		}
		elseif($bytes == 1){
			$output = $bytes . ' byte';
		}
		else{
			$output = '0 bytes';
		}

		return $output;
	}
}
