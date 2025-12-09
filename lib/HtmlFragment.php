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
}
