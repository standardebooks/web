<?
use function Safe\preg_replace;
use function Safe\simplexml_load_string;

/**
 * An `HtmlFragment` can be any stretch of valid HTML text or elements, like `<p>Hello</p>` or `Some <b>bold</b> text`.
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
	 * @throws Exceptions\InvalidHtmlException If the HTML fragment is invalid.
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

		// Some newsletter specific conversions first.
		// Replace footer `<div>` with `<hr>`.
		$this->_Value = preg_replace('|<div class="footer">(.+?)</div>|ius', '<hr/>\1', $this->_Value);

		// Replace `<strong>` with `@class` with just `<strong>`.
		$this->_Value = preg_replace('|<strong class="[^"]+">|ius', '<strong>', $this->_Value);

		// Replace all `<divs>` with `<p>`.
		$this->_Value = preg_replace('|<div[^>]*?>(.+?)</div>|ius', '<p>\1</p>', $this->_Value);

		// Replace `<img>` with its `@alt` text.
		$this->_Value = preg_replace('|<img[^>]*?alt="([^"]+?)"[^>]*?>|ius', '\1', $this->_Value);

		// `ltrim()` node contents.
		$count = 1;
		while($count){
			$this->_Value = preg_replace('|(<[a-z]+[^>]*?>)\s+(.+?)(</[a-z]+>)|ius', '\1\2\3', $this->_Value, -1, $count);
		}
		// `rtrim()` node contents.
		$count = 1;
		while($count){
			$this->_Value = preg_replace('|(<[a-z]+[^>]*?>)(.+?)\s+(</[a-z]+>)|ius', '\1\2\3', $this->_Value, -1, $count);
		}

		$output = $converter->parseString($this->_Value);

		// Replace list style.
		$output = preg_replace('/^ +\* /ium', '- ', $output);

		return new Markdown($output);
	}
}
