<?
use function Safe\preg_match;

/**
 * A complete HTML document beginning with a doctype and a root element.
 *
 * Contrast to an `HtmlFragment`, which doesn't require those two items.
 */
final class HtmlDocument extends HtmlFragment{
	/**
	 * @throws Exceptions\HtmlInvalidException If the `HtmlDocument` is invalid.
	 */
	public function Validate(): void{
		$errors = [];
		try{
			parent::Validate();
		}
		catch(Exceptions\HtmlInvalidException $ex){
			$errors[] = $ex;
		}

		if(!preg_match('/^<!DOCTYPE html>\s*<html[\s>]/ius', $this->_Value)){
			$errors[] = new Exceptions\HtmlInvalidException('HTML must begin with a doctype and root element.');
		}

		if(sizeof($errors) > 0){
			$message = '';
			foreach($errors as $error){
				$message .= $error->RawMessage . '; ';
			}
			$message = rtrim($message, '; ');

			throw new Exceptions\HtmlInvalidException($message);
		}
	}
}
