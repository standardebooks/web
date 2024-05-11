<?
use function Safe\ini_get;
use function Safe\preg_match;

class HttpInput{
	/**
	 * @param ?array<HttpMethod> $allowedHttpMethods An array containing a list of allowed HTTP methods, or null if any valid HTTP method is allowed.
	 * @param bool $throwException If true, in case of errors throw an exception; if false, in case of errors output HTTP 405 and exit the script immediately.
	 * @throws Exceptions\InvalidHttpMethodException If the HTTP method is not recognized.
	 * @throws Exceptions\HttpMethodNotAllowedException If the HTTP method is not in the list of allowed methods.
	 */
	public static function ValidateRequestMethod(?array $allowedHttpMethods = null, bool $throwException = false): HttpMethod{
		try{
			$requestMethod = HttpMethod::from($_POST['_method'] ?? $_SERVER['REQUEST_METHOD']);
			if($allowedHttpMethods !== null){
				$isRequestMethodAllowed = false;
				foreach($allowedHttpMethods as $allowedHttpMethod){
					if($requestMethod == $allowedHttpMethod){
						$isRequestMethodAllowed = true;
					}
				}

				if(!$isRequestMethodAllowed){
					throw new Exceptions\HttpMethodNotAllowedException();
				}
			}
		}
		catch(\ValueError | Exceptions\HttpMethodNotAllowedException $ex){
			if($throwException){
				if($ex instanceof \ValueError){
					throw new Exceptions\InvalidHttpMethodException();
				}
				else{
					throw $ex;
				}
			}
			else{
				if($allowedHttpMethods !== null){
					header('Allow: ' . implode(',', array_map(fn($httpMethod): string => $httpMethod->value, $allowedHttpMethods)));
				}
				http_response_code(405);
				exit();
			}
		}

		return $requestMethod;
	}

	public static function GetMaxPostSize(): int{ // bytes
		$post_max_size = ini_get('post_max_size');
		$unit = substr($post_max_size, -1);
		$size = (int) substr($post_max_size, 0, -1);

		return match ($unit){
			'g', 'G' => $size * 1024 * 1024 * 1024,
			'm', 'M' => $size * 1024 * 1024,
			'k', 'K' => $size * 1024,
			default => $size
		};
	}

	public static function IsRequestTooLarge(): bool{
		if(empty($_POST) || empty($_FILES)){
			if($_SERVER['CONTENT_LENGTH'] > self::GetMaxPostSize()){
				return true;
			}
		}

		return false;
	}

	public static function RequestType(): HttpRequestType{
		return preg_match('/\btext\/html\b/ius', $_SERVER['HTTP_ACCEPT'] ?? '') ? HttpRequestType::Web : HttpRequestType::Rest;
	}

	public static function Str(HttpVariableSource $set, string $variable, bool $allowEmptyString = false): ?string{
		$var = self::GetHttpVar($variable, HttpVariableType::String, $set);

		if(is_array($var)){
			return null;
		}

		if(!$allowEmptyString && $var == ''){
			return null;
		}

		return $var;
	}

	public static function Int(HttpVariableSource $set, string $variable): ?int{
		return self::GetHttpVar($variable, HttpVariableType::Integer, $set);
	}

	public static function Bool(HttpVariableSource $set, string $variable): ?bool{
		return self::GetHttpVar($variable, HttpVariableType::Boolean, $set);
	}

	public static function Dec(HttpVariableSource $set, string $variable): ?float{
		return self::GetHttpVar($variable, HttpVariableType::Decimal, $set);
	}

	/**
	* @param string $variable
	* @return array<string>
	*/
	public static function Array(HttpVariableSource $set, string $variable): ?array{
		return self::GetHttpVar($variable, HttpVariableType::Array, $set);
	}

	private static function GetHttpVar(string $variable, HttpVariableType $type, HttpVariableSource $set): mixed{
		$vars = [];

		switch($set){
			case HttpVariableSource::Get:
				$vars = $_GET;
				break;
			case HttpVariableSource::Post:
				$vars = $_POST;
				break;
			case HttpVariableSource::Cookie:
				$vars = $_COOKIE;
				break;
			case HttpVariableSource::Session:
				$vars = $_SESSION;
				break;
		}

		if(isset($vars[$variable])){
			if($type == HttpVariableType::Array && is_array($vars[$variable])){
				// We asked for an array, and we got one
				return $vars[$variable];
			}
			elseif($type !== HttpVariableType::Array && is_array($vars[$variable])){
				// We asked for not an array, but we got an array
				return null;
			}
			else{
				$var = trim($vars[$variable]);
			}

			switch($type){
				case HttpVariableType::String:
					return $var;
				case HttpVariableType::Integer:
					// Can't use ctype_digit because we may want negative integers
					if(is_numeric($var) && mb_strpos($var, '.') === false){
						try{
							return intval($var);
						}
						catch(Exception){
							return null;
						}
					}
					break;
				case HttpVariableType::Boolean:
					if($var === '0' || strtolower($var) == 'false' || strtolower($var) == 'off'){
						return false;
					}
					else{
						return true;
					}
				case HttpVariableType::Decimal:
					if(is_numeric($var)){
						try{
							return floatval($var);
						}
						catch(Exception){
							return null;
						}
					}
					break;
			}
		}

		return null;
	}
}
