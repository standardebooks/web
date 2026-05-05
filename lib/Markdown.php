<?

/**
 * A `Markdown` object contains Markdown text that can be rendered as an HTML fragment.
 */
final class Markdown{
	private static Parsedown $_MarkdownParser;
	private string $_Value;

	/**
	 * Create a new Markdown value object.
	 */
	public function __construct(string $value = ''){
		$this->_Value = trim($value);
	}

	/**
	 * Return the original Markdown text.
	 */
	public function __toString(): string{
		return $this->_Value;
	}

	/**
	 * Convert this Markdown text into an HTML fragment.
	 *
	 * @param bool $inline **`FALSE`** to wrap the fragment in a `<p>` root node.
	 */
	public function ToHtmlFragment(bool $inline = false): HtmlFragment{
		$string = '';

		if(!isset(self::$_MarkdownParser)){
			self::$_MarkdownParser = new Parsedown();
			self::$_MarkdownParser->setSafeMode(true);
		}

		if($inline){
			$string = self::$_MarkdownParser->line($this->_Value);
		}
		else{
			$string = self::$_MarkdownParser->text($this->_Value);
		}

		return new HtmlFragment($string);
	}
}
