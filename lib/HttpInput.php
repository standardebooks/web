<?
use Safe\DateTimeImmutable;

use function Safe\file_get_contents;
use function Safe\ini_get;
use function Safe\glob;
use function Safe\preg_match;
use function Safe\preg_replace;
use function Safe\mb_convert_encoding;

class HttpInput{
	/**
	 * If we received a `DELETE`, `PATCH`, or `PUT` request, parse the request body into `$_POST`.
	 *
	 * This can't handle file uploads, which due to PHP limitations *must* be sent via `POST`.
	 */
	public static function Initialize(): void{
		/** @var string $contentType */
		$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
		if(
			isset($_SERVER['REQUEST_METHOD'])
			&&
			(
				$_SERVER['REQUEST_METHOD'] == Enums\HttpMethod::Delete->value
				||
				$_SERVER['REQUEST_METHOD'] == Enums\HttpMethod::Patch->value
				||
				$_SERVER['REQUEST_METHOD'] == Enums\HttpMethod::Put->value
			)
			&&
			preg_match('/^application\/x-www-form-urlencoded(;|$)/', $contentType)
		){
			parse_str(file_get_contents('php://input'), $_POST);
		}
	}

	/**
	 * Calculate the HTTP method of the request, then include `<METHOD>.php` and exit.
	 */
	public static function DispatchRest(): void{
		try{
			$httpMethod = HttpInput::ValidateRequestMethod(null, true);

			$filename = mb_strtolower($httpMethod->value) . '.php';

			if(!file_exists($filename)){
				throw new Exceptions\HttpMethodNotAllowedException();
			}

			if($httpMethod == Enums\HttpMethod::Post){
				// If we're a HTTP `POST`, then we got here from a `POST` request initially, so just continue.
				return;
			}

			/** @phpstan-ignore-next-line */
			include($filename);

			exit();
		}
		catch(Exceptions\HttpMethodNotAllowedException){
			$filenames = glob('{delete,get,patch,post,put}.php', GLOB_BRACE);

			if(sizeof($filenames) > 0){
				header('Allow: ' . implode(',', array_map(fn($filename): string => mb_strtoupper(preg_replace('/^([a-z]+)[\.\-].+$/i', '\1', $filename)), $filenames)));
			}

			http_response_code(Enums\HttpCode::MethodNotAllowed->value);
			exit();
		}
	}

	/**
	 * Check that the request's HTTP method is in a list of allowed HTTP methods.
	 *
	 * @param ?array<Enums\HttpMethod> $allowedHttpMethods An array containing a list of allowed HTTP methods, or null if any valid HTTP method is allowed.
	 * @param bool $throwException If the request HTTP method isn't allowed, then throw an exception; otherwise, output HTTP 405 and exit the script immediately.
	 * @throws Exceptions\HttpMethodNotAllowedException If the HTTP method is recognized but not allowed, and `$throwException` is `true`.
	 */
	public static function ValidateRequestMethod(?array $allowedHttpMethods = null, bool $throwException = false): Enums\HttpMethod{
		try{
			/** @var string $requestMethodString */
			$requestMethodString = $_POST['_method'] ?? $_GET['_method'] ?? $_SERVER['REQUEST_METHOD'];
			$requestMethod = Enums\HttpMethod::from($requestMethodString);
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
					throw new Exceptions\HttpMethodNotAllowedException();
				}
				else{
					throw $ex;
				}
			}
			else{
				if($allowedHttpMethods !== null){
					header('Allow: ' . implode(',', array_map(fn($httpMethod): string => $httpMethod->value, $allowedHttpMethods)));
				}
				http_response_code(Enums\HttpCode::MethodNotAllowed->value);
				exit();
			}
		}

		return $requestMethod;
	}

	/**
	 * @return int The maximum size for an HTTP `POST` request, in bytes.
	 */
	public static function GetMaxPostSize(): int{
		$post_max_size = ini_get('upload_max_filesize');
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
		elseif(sizeof($_FILES) > 0){
			// We received files but may have an error because the size exceeded our limit.
			foreach($_FILES as $file){
				/** @var array<string, int> $file */
				$error = $file['error'] ?? UPLOAD_ERR_OK;

				if($error == UPLOAD_ERR_INI_SIZE || $error == UPLOAD_ERR_FORM_SIZE){
					return true;
				}
			}
		}

		return false;
	}

	public static function GetRequestType(): Enums\HttpRequestType{
		/** @var string $httpAccept */
		$httpAccept = $_SERVER['HTTP_ACCEPT'] ?? '';
		return preg_match('/\btext\/html\b/ius',$httpAccept) ? Enums\HttpRequestType::Web : Enums\HttpRequestType::Rest;
	}

	/**
	 * Get a string from an HTTP variable set.
	 *
	 * If the variable is set but empty, returns `null` unless `$allowEmptyString` is **`TRUE`**, in which case it returns an empty string.
	 *
	 * @param Enums\HttpVariableSource $set
	 * @param string $variable
	 * @param bool $allowEmptyString If the variable exists but is empty, return an empty string instead of `null`.
	 */
	public static function Str(Enums\HttpVariableSource $set, string $variable, bool $allowEmptyString = false): ?string{
		$var = self::GetHttpVar($variable, Enums\HttpVariableType::String, $set);

		if(is_array($var)){
			return null;
		}

		if(!$allowEmptyString && $var == ''){
			return null;
		}

		/** @var ?string $var */
		return $var;
	}

	public static function Int(Enums\HttpVariableSource $set, string $variable): ?int{
		/** @var ?int */
		return self::GetHttpVar($variable, Enums\HttpVariableType::Integer, $set);
	}

	public static function Bool(Enums\HttpVariableSource $set, string $variable): ?bool{
		/** @var ?bool */
		return self::GetHttpVar($variable, Enums\HttpVariableType::Boolean, $set);
	}

	public static function Dec(Enums\HttpVariableSource $set, string $variable): ?float{
		/** @var ?float */
		return self::GetHttpVar($variable, Enums\HttpVariableType::Decimal, $set);
	}

	public static function Date(Enums\HttpVariableSource $set, string $variable): ?DateTimeImmutable{
		/** @var ?DateTimeImmutable */
		return self::GetHttpVar($variable, Enums\HttpVariableType::DateTime, $set);
	}

	/**
	 * Return an object of type `$class` from `$_SESSION`, or `null` of no object of that type exists in `$_SESSION`.
	 *
	 * @template T of object
	 * @param string $variable
	 * @param class-string<T>|array<class-string<T>> $class The class of the object to return, or an array of possible classes to return.
	 *
	 * @return ?T An object of type `$class`, or `null` if no object of that type exists in `$_SESSION`.
	 */
	public static function SessionObject(string $variable, string|array $class): ?object{
		if(!is_array($class)){
			$class = [$class];
		}

		$object = $_SESSION[$variable] ?? null;

		if(is_object($object)){
			foreach($class as $c){
				if(is_a($object, $c)){
					return $object;
				}
			}
		}

		return null;
	}

	/**
	 * Returns the absolute path of the requested file upload, or `null` if there isn't one.
	 *
	 * @throws Exceptions\InvalidFileUploadException If there is a file upload present, but the upload somehow failed.
	 */
	public static function File(string $variable): ?string{
		$filePath = null;

		if(isset($_FILES[$variable])){
			/** @var array{'error': int, 'size': int, 'tmp_name': string} $file */
			$file = $_FILES[$variable];

			if($file['size'] > 0){
				if(!is_uploaded_file($file['tmp_name']) || $file['error'] > UPLOAD_ERR_OK){
					throw new Exceptions\InvalidFileUploadException();
				}

				$filePath = $file['tmp_name'];
			}
		}

		return $filePath;
	}

	/**
	* @param string $variable
	* @return array<string>
	*/
	public static function Array(Enums\HttpVariableSource $set, string $variable): ?array{
		/** @var array<string> */
		return self::GetHttpVar($variable, Enums\HttpVariableType::Array, $set);
	}

	/**
	 * @return array<string>|array<int>|array<float>|array<bool>|array<mixed>|string|int|float|bool|DateTimeImmutable|null
	 */
	private static function GetHttpVar(string $variable, Enums\HttpVariableType $type, Enums\HttpVariableSource $set): mixed{
		$vars = [];

		switch($set){
			case Enums\HttpVariableSource::Get:
				$vars = $_GET;
				break;
			case Enums\HttpVariableSource::Post:
				$vars = $_POST;
				break;
			case Enums\HttpVariableSource::Cookie:
				$vars = $_COOKIE;
				break;
			case Enums\HttpVariableSource::Session:
				$vars = $_SESSION;
				break;
		}

		if(isset($vars[$variable])){
			if($type == Enums\HttpVariableType::Array && is_array($vars[$variable])){
				// We asked for an array, and we got one.
				return $vars[$variable];
			}
			elseif($type !== Enums\HttpVariableType::Array && is_array($vars[$variable])){
				// We asked for not an array, but we got an array.
				return null;
			}
			elseif(is_string($vars[$variable])){
				// HTML `<textarea>`s encode newlines as `\r\n`, i.e. *two* characters, when submitting form data. However jQuery's `.val()` and HTML's `@maxlength` treat newlines as *one* character. So, strip `\r` here so that character lengths align between what the browser reports, and what it actually sends. This also solves column length issues when storing in the DB.
				$var = trim(str_replace("\r", "", $vars[$variable]));
			}
			else{
				$var = $vars[$variable];
			}

			switch($type){
				case Enums\HttpVariableType::String:
					if(!is_string($var)){
						return '';
					}

					// Attempt to fix broken UTF8 strings, often passed by bots and scripts.
					// Broken UTF8 can cause exceptions in functions like `preg_replace()`.
					try{
						return mb_convert_encoding($var, 'utf-8');
					}
					catch(\Safe\Exceptions\MbstringException){
						return '';
					}
				case Enums\HttpVariableType::Integer:
					// Can't use `ctype_digit()` because we may want negative integers.
					if(is_numeric($var) && mb_strpos((string)$var, '.') === false){
						return intval($var);
					}
					break;
				case Enums\HttpVariableType::Boolean:
					if($var === false || (is_string($var) && ($var === '0' || strtolower($var) == 'false' || strtolower($var) == 'off'))){
						return false;
					}
					else{
						return true;
					}
				case Enums\HttpVariableType::Decimal:
					if(is_numeric($var)){
						return floatval($var);
					}
					break;
				case Enums\HttpVariableType::DateTime:
					if(is_string($var) && $var != ''){
						try{
							return new DateTimeImmutable($var);
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
