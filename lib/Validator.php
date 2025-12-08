<?
use function Safe\preg_match;
use function Safe\preg_replace;
use function Safe\simplexml_load_string;

class Validator{
	public static function IsValidEmail(?string $email): bool{
		if(filter_var($email, FILTER_VALIDATE_EMAIL) !== false){
			return true;
		}

		return false;
	}

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
}
