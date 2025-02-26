<?
use function Safe\ob_end_clean;
use function Safe\ob_start;

class Template{
	/**
	* @param array<mixed> $arguments
	*/
	protected static function Get(string $templateName, array $arguments = []): string{
		// Expand the passed variables to make them available to the included template.
		// We use these funny names so that we can use 'name' and 'value' as template variables if we want to.
		foreach($arguments as $innerName => $innerValue){
			$$innerName = $innerValue;
		}

		ob_start();
		include(TEMPLATES_PATH . '/' . $templateName . '.php');
		$contents = ob_get_contents() ?: '';
		ob_end_clean();

		return $contents;
	}

	/**
	* @param array<mixed> $arguments
	*/
	public static function __callStatic(string $function, array $arguments): string{
		if(isset($arguments[0]) && is_array($arguments[0])){
			return self::Get($function, $arguments[0]);
		}
		else{
			return self::Get($function, $arguments);
		}
	}

	/**
	 * Exit the script while outputting the given HTTP code.
	 *
	 * @param bool $showPage If **`TRUE`**, show a special page given the HTTP code (like a 404 page).
	 *
	 * @return never
	 */
	public static function ExitWithCode(Enums\HttpCode $httpCode, bool $showPage = true, Enums\HttpRequestType $requestType = Enums\HttpRequestType::Web): void{
		http_response_code($httpCode->value);

		if($requestType == Enums\HttpRequestType::Web && $showPage){
			switch($httpCode){
				case Enums\HttpCode::Forbidden:
					include(WEB_ROOT . '/403.php');
					break;
				case Enums\HttpCode::NotFound:
					include(WEB_ROOT . '/404.php');
					break;
			}
		}

		exit();
	}

	/**
	 * Redirect the user to the login page.
	 *
	 * @param bool $redirectToDestination After login, redirect the user to the page they came from.
	 * @param ?string $destinationUrl If `$redirectToDestination` is **`TRUE`**, redirect to this URL instead of hte page they came from.
	 *
	 * @return never
	 */
	public static function RedirectToLogin(bool $redirectToDestination = true, ?string $destinationUrl = null): void{
		if($redirectToDestination){
			if($destinationUrl === null){
				/** @var string $destinationUrl */
				$destinationUrl = $_SERVER['SCRIPT_URL'];
			}

			header('Location: /sessions/new?redirect=' . urlencode($destinationUrl));
		}
		else{
			header('Location: /sessions/new');
		}

		exit();
	}

	public static function IsEreaderBrowser(): bool{
		/** @var string $httpUserAgent */
		$httpUserAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
		return $httpUserAgent != '' && (strpos($httpUserAgent, "Kobo") !== false || strpos($httpUserAgent, "Kindle") !== false);
	}
}
