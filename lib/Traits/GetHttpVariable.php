<?
namespace Traits;

use Safe\DateTime;
use Safe\DateTimeImmutable;
use function Safe\mb_convert_encoding;

/**
 * Used for sharing code between HTTP variable interfaces.
 */
trait GetHttpVariable{
	/**
	 * Return a request variable converted to the requested scalar type, or an object matching the requested type. If the variable is not present *or* is present but empty (i.e. an empty string), return `null`.
	 *
	 * @param string $variable The name of the variable to get.
	 * @param array<mixed> $variables The key/value array from which to get the variable.
	 * @param 'array'|'bool'|'float'|'int'|'string'|'empty-string'|'date'|'DateTimeImmutable'|class-string<object>|array<'array'|'bool'|'float'|'int'|'string'|'empty-string'|'date'|'DateTimeImmutable'|class-string<object>> $type The type of value to return, or a list of acceptable types to check in order. The special type `empty-string` returns an empty string instead of `null` if the variable exists but is empty.
	 *
	 * @return ($type is 'array' ? array<mixed>|null : ($type is 'bool' ? bool|null : ($type is 'float' ? float|null : ($type is 'int' ? int|null : ($type is 'string' ? string|null : ($type is 'empty-string' ? string|null : ($type is 'date'|'DateTimeImmutable' ? DateTimeImmutable|null : ($type is class-string<object> ? object|null : mixed))))))))
	 */
	public function GetHttpVariable(string $variable, array $variables, string|array $type = 'string'): mixed{
		if(is_array($type)){
			foreach($type as $potentialType){
				$value = $this->Get($variable, $potentialType);
				if($value !== null){
					return $value;
				}
			}

			return null;
		}

		if(isset($variables[$variable])){
			if($type == 'array' && is_array($variables[$variable])){
				// We asked for an array, and we got one.
				return $variables[$variable];
			}
			elseif($type !== 'array' && is_array($variables[$variable])){
				// We asked for not an array, but we got an array.
				return null;
			}
			elseif(is_string($variables[$variable])){
				// HTML `<textarea>`s encode newlines as `\r\n`, i.e. *two* characters, when submitting form data. However jQuery's `.val()` and HTML's `@maxlength` treat newlines as *one* character. So, strip `\r` here so that character lengths align between what the browser reports, and what it actually sends. This also solves column length issues when storing in the DB.
				$var = trim(str_replace("\r", "", $variables[$variable]));
			}
			else{
				$var = $variables[$variable];
			}

			switch($type){
				case 'string':
					if(!is_string($var)){
						return null;
					}

					// Attempt to fix broken UTF8 strings, often passed by bots and scripts.
					// Broken UTF8 can cause exceptions in functions like `preg_replace()`.
					try{
						$str = mb_convert_encoding($var, 'utf-8');
					}
					catch(\Safe\Exceptions\MbstringException){
						return null;
					}

					if($str == ''){
						return null;
					}

					return $str;
				case 'empty-string':
					if(!is_string($var)){
						return null;
					}

					// Attempt to fix broken UTF8 strings, often passed by bots and scripts.
					// Broken UTF8 can cause exceptions in functions like `preg_replace()`.
					try{
						$str = mb_convert_encoding($var, 'utf-8');
					}
					catch(\Safe\Exceptions\MbstringException){
						$str = '';
					}

					return $str;
				case 'int':
					// Can't use `ctype_digit()` because we may want negative integers.
					if(is_numeric($var) && mb_strpos((string)$var, '.') === false){
						return intval($var);
					}
					break;
				case 'bool':
					if($var === false || (is_string($var) && ($var === '0' || strtolower($var) == 'false' || strtolower($var) == 'off'))){
						return false;
					}
					else{
						// Existence of the variable name, without a value, is the same as **`TRUE`**.
						return true;
					}
				case 'float':
					if(is_numeric($var)){
						return floatval($var);
					}
					break;
				case 'date':
				case 'DateTime': // Catch non-Safe requests.
				case DateTime::class:
				case 'DateTimeImmutable': // Catch non-Safe requests.
				case DateTimeImmutable::class:
					if(is_string($var) && $var != ''){
						try{
							return new DateTimeImmutable($var);
						}
						catch(\Exception){
							return null;
						}
					}
					elseif(is_object($var) && is_a($var, $type)){
						return $var;
					}
					break;
				default:
					// Get an object of a specific class, usually from `$_SESSION`.
					if(is_object($var) && class_exists($type) && is_a($var, $type)){
						return $var;
					}
			}
		}

		return null;
	}
}
