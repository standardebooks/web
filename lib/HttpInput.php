<?
class HttpInput{
	public static function Str(string $type, string $variable, bool $allowEmptyString = true, string $default = null): ?string{
		$var = self::GetHttpVar($variable, HTTP_VAR_STR, $type, $default);

		if(is_array($var)){
			return $default;
		}

		if(!$allowEmptyString && $var === ''){
			return null;
		}

		return $var;
	}

	public static function Int(string $type, string $variable, int $default = null): ?int{
		return self::GetHttpVar($variable, HTTP_VAR_INT, $type, $default);
	}

	public static function Bool(string $type, string $variable, bool $default = null): ?bool{
		return self::GetHttpVar($variable, HTTP_VAR_BOOL, $type, $default);
	}

	public static function Dec(string $type, string $variable, float $default = null): ?float{
		return self::GetHttpVar($variable, HTTP_VAR_DEC, $type, $default);
	}

	/**
	 * @return array<string>
	 */
	public static function GetArray(string $variable, array $default = null): ?array{
		return self::GetHttpVar($variable, HTTP_VAR_ARRAY, GET, $default);
	}

	private static function GetHttpVar(string $variable, int $type, string $set, $default){
		$vars = [];

		switch($set){
			case GET:
				$vars = $_GET;
				break;
			case POST:
				$vars = $_POST;
				break;
			case COOKIE:
				$vars = $_COOKIE;
				break;
		}

		if(isset($vars[$variable])){
			if($type == HTTP_VAR_ARRAY && is_array($vars[$variable])){
				// We asked for an array, and we got one
				return $vars[$variable];
			}
			elseif($type !== HTTP_VAR_ARRAY && is_array($vars[$variable])){
				// We asked for not an array, but we got an array
				return $default;
			}
			else{
				$var = trim($vars[$variable]);
			}

			switch($type){
				case HTTP_VAR_STR:
					return $var;
				case HTTP_VAR_INT:
					// Can't use ctype_digit because we may want negative integers
					if(is_numeric($var) && mb_strpos($var, '.') === false){
						try{
							return intval($var);
						}
						catch(\Exception $ex){
							return $default;
						}
					}
					break;
				case HTTP_VAR_BOOL:
					if($var === '0' || strtolower($var) == 'false' || strtolower($var) == 'off'){
						return false;
					}
					else{
						return true;
					}
				case HTTP_VAR_DEC:
					if(is_numeric($var)){
						try{
							return floatval($var);
						}
						catch(\Exception $ex){
							return $default;
						}
					}
					break;
			}
		}

		return $default;
	}
}
