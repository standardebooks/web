<?
use function Safe\preg_match;
use function Safe\preg_replace;
use function Safe\simplexml_load_string;

class Formatter{
	private static Transliterator $_Transliterator;
	private static Parsedown $_MarkdownParser;
	private static NumberFormatter $_NumberFormatter;

	/**
	 * @throws Exceptions\InvalidHtmlException If the HTML fragment is invalid.
	 */
	public static function ValidateHtmlFragment(string $string, bool $mustStartWithElement = true): void{
		$errorString = '';

		libxml_use_internal_errors(true);
		libxml_clear_errors();

		// Remove HTML entities from the string, XML parser can't handle them.
		$string = preg_replace('/&[#a-z0-9]+;/ius', '', trim($string));

		// Remove HTML doctype, XML parser can't handle it.
		$string = trim(str_ireplace('<!DOCTYPE html>', '', $string));

		// SimpleXML requires a root element.
		try{
			simplexml_load_string('<html>' . $string . '</html>');
		}
		catch(\Safe\Exceptions\SimplexmlException){
			$libXmlErrors = libxml_get_errors();

			foreach($libXmlErrors as $libXmlError){
				$errorString .= trim($libXmlError->message) . '; ';
			}
		}

		// Test the case where there's no HTML at all.
		if($mustStartWithElement && !preg_match('/^</ius', $string)){
			$errorString .= 'String must start with HTML element';
		}

		$errorString = rtrim($errorString, '; ');

		if($errorString != ''){
			$error = new Exceptions\InvalidHtmlException($errorString . '.');
			throw $error;
		}
	}

	/**
	 * Remove diacritics from a string, leaving the now-unaccented characters in place.
	 */
	public static function RemoveDiacritics(string $text): string{
		if(!isset(Formatter::$_Transliterator)){
			$transliterator = Transliterator::createFromRules(':: Any-Latin; :: Latin-ASCII; :: NFD; :: [:Nonspacing Mark:] Remove; :: Lower(); :: NFC;', Transliterator::FORWARD);

			if($transliterator === null){
				return $text;
			}
			else{
				Formatter::$_Transliterator = $transliterator;
			}
		}

		$transliteratedText = Formatter::$_Transliterator->transliterate($text);

		if($transliteratedText === false){
			return $text;
		}
		else{
			return $transliteratedText;
		}
	}

	/**
	 * Remove diacritics and non-alphanumeric characters.
	 */
	public static function RemoveDiacriticsAndNonalphanumerics(string $text): string{
		return trim(preg_replace('|[^a-zA-Z0-9 ]|ius', '', Formatter::RemoveDiacritics($text)));
	}

	/**
	 * Escape a string so that it's appropriate to use in a URL slug.
	 *
	 * This does the following to the string:
	 *
	 * 1. Removes any diacritics.
	 *
	 * 2. Removes apostrophes.
	 *
	 * 3. Converts the string to lowercase.
	 *
	 * 4. Converts any non-digit, non-letter character to a space.
	 *
	 * 5. Converts any sequence of one or more spaces to a single dash.
	 */
	public static function MakeUrlSafe(string $text): string{
		// Remove accent characters.
		$text = Formatter::RemoveDiacritics($text);

		// Remove apostrophes.
		$text = preg_replace('/[\'’]/u', '', $text);

		// Trim and convert to lowercase.
		$text = mb_strtolower(trim($text));

		// Then convert any non-digit, non-letter character to a space.
		$text = preg_replace('/[^0-9a-zA-Z]/ius', ' ', $text);

		// Then convert any instance of one or more space to dash.
		$text = preg_replace('/\s+/ius', '-', $text);

		// Finally, trim dashes.
		$text = trim($text, '-');

		return $text;
	}

	/**
	 * Escape a string so that it's safe to output directly into an HTML document.
	 */
	public static function EscapeHtml(?string $text): string{
		return htmlspecialchars(trim($text ?? ''), ENT_QUOTES, 'utf-8');
	}

	/**
	 * Escape a string so that it's safe to output directly into an XML document. Note that this is **not the same** as escaping for HTML. Any query strings in URLs should already be URL-encoded, for example `?foo=bar+baz&x=y`.
	 */
	public static function EscapeXml(?string $text): string{
		return htmlspecialchars(trim($text ?? ''), ENT_QUOTES|ENT_XML1, 'utf-8');
	}

	/**
	 * Escape a string for use in Markdown.
	 */
	public static function EscapeMarkdown(?string $text): string{
		if($text === null){
			return '';
		}

		return str_replace(
			['\\', '-', '#', '*', '+', '`', '.', '[', ']', '(', ')', '!', '<', '>', '_', '{', '}', '|'],
			['\\\\', '\-', '\#', '\*', '\+', '\`', '\.', '\[', '\]', '\(', '\)', '\!', '\<', '\>', '\_', '\{', '\}', '\|'],
		$text);
	}

	/**
	 * Convert a string of Markdown into an HTML fragment.
	 */
	public static function MarkdownToHtml(?string $text, bool $inline = false): string{
		if(!isset(Formatter::$_MarkdownParser)){
			Formatter::$_MarkdownParser = new Parsedown();
			Formatter::$_MarkdownParser->setSafeMode(true);
		}

		if($inline){
			return Formatter::$_MarkdownParser->line($text);
		}else{
			return Formatter::$_MarkdownParser->text($text);
		}
	}

	/**
	 * Given a number of bytes, return a string containing a human-readable filesize.
	 *
	 * @see https://stackoverflow.com/a/5501447
	 */
	public static function ToFileSize(?int $bytes): string{
		$output = '';

		if($bytes >= 1073741824){
			$output = number_format(round($bytes / 1073741824, 1), 1) . 'G';
		}
		elseif($bytes >= 1048576){
			$output = number_format(round($bytes / 1048576, 1), 1) . 'M';
		}
		elseif($bytes >= 1024){
			$output = number_format($bytes / 1024, 0) . 'KB';
		}
		elseif($bytes > 1){
			$output = $bytes . 'B';
		}
		elseif($bytes == 1){
			$output = $bytes . 'B';
		}
		else{
			$output = '0B';
		}

		return $output;
	}

	/**
	 * Format a valid IPv4 address to IPv6. Other strings are unchanged.
	 */
	public static function ToIpv6(?string $ipAddress): ?string{
		if(filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)){
			return '::ffff:' . $ipAddress;
		}

		return $ipAddress;
	}

	/**
	 * Format a float into a USD currency string. The result is prepended with `$`.
	 *
	 * @param ?float $amount The amount to format.
	 * @param bool $trimZeroCents If `$amount` has zero cents, don't include the cents value.
	 */
	public static function FormatCurrency(?float $amount, bool $trimZeroCents = false): string{
		if($amount === null){
			$amount = 0;
		}

		if(!isset(Formatter::$_NumberFormatter)){
			Formatter::$_NumberFormatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
		}

		$output = Formatter::$_NumberFormatter->formatCurrency($amount, 'USD');

		if($output === false){
			$output = '$0.00';
		}

		if($trimZeroCents){
			$output = preg_replace('/\.00$/u', '', $output);
		}

		return $output;
	}
}
