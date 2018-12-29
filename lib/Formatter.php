<?
class Formatter{
	public static function MakeUrlSafe(string $text): string{
		// Remove accent characters
		$text = @iconv('UTF-8', 'ASCII//TRANSLIT', $text);

		// Trim and convert to lowercase
		$text = mb_strtolower(trim($text));

		// Remove apostrophes
		$text = preg_replace("/['’]/ius", '', $text);

		// Then convert any non-digit, non-letter character to a space
		$text = preg_replace('/[^0-9a-zA-Z]/ius', ' ', $text);

		// Then convert any instance of one or more space to dash
		$text = preg_replace('/\s+/ius', '-', $text);

		// Finally, trim dashes
		$text = trim($text, '-');

		return $text;
	}

	public static function ToPlainText(string $text): string{
		return htmlspecialchars(trim($text), ENT_QUOTES, 'UTF-8');
	}
}
?>
