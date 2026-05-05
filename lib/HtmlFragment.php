<?
use function Safe\preg_replace;
use function Safe\simplexml_load_string;

/**
 * Any stretch of valid HTML elements or text, like `<p>Hello</p>` or `Some <b>bold</b> text`.
 */
class HtmlFragment{
	protected string $_Value;

	public function __construct(string $value = ''){
		$this->_Value = trim($value);
	}

	public function __toString(): string{
		return $this->_Value;
	}

	/**
	 * @throws Exceptions\InvalidHtmlException If the `HtmlFragment` is invalid.
	 */
	public function Validate(): void{
		$string = $this->_Value;
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

		$errorString = rtrim($errorString, '; ');

		if($errorString != ''){
			$error = new Exceptions\InvalidHtmlException($errorString . '.');
			throw $error;
		}
	}

	/**
	 * Convert this HTML fragment to the equivalent Markdown.
	 */
	public function ToMarkdown(): Markdown{
		$converter = new Markdownify\Converter(Markdownify\Converter::LINK_IN_PARAGRAPH, 0, false); // Have to use `0` instead of a bool to satisfy type check.

		// TODO: In PHP >= 8.4, use `\Dom\HTMLDocument` to create a DOM and make these adjustments that way instead.

		$html = $this->_Value;

		// Remove doctypes.
		$html = preg_replace('|<!doctype[^>]*?>|ius', '', $html);

		// Replace `<img alt="">` with its `@alt` text.
		$html = preg_replace('|<img[^>]+?alt="([^"]+?)"[^>]*?>|ius', '\1', $html);

		// Remove any `<img>` without `@alt` text.
		$html = preg_replace('|<img[^>]*?>|ius', '', $html);

		// Remove any stray closing `</img>`.
		$html = preg_replace('|</img>|ius', '', $html);

		// Remove `<a>` that have no anchor text; these might have been image links.
		$html = preg_replace('|<a\b[^>]+?>\s*</a>|ius', '', $html);

		// `ltrim()` node contents.
		$count = 1;
		while($count){
			$html = preg_replace('|(<[a-z]+[^>]*?>)\s+(.+?)(</[a-z]+>)|ius', '\1\2\3', $html, -1, $count);
		}

		// `rtrim()` node contents.
		$count = 1;
		while($count){
			$html = preg_replace('|(<[a-z]+[^>]*?>)(.+?)\s+(</[a-z]+>)|ius', '\1\2\3', $html, -1, $count);
		}

		$output = $converter->parseString($html);

		// Replace `*` list bullets with `-`.
		$output = preg_replace('/^ +\* /ium', '- ', $output);

		return new Markdown($output);
	}
}
