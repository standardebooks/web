<?
use function Safe\ob_end_clean;

class Template{
	/**
	* @param string $templateName
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
	* @param string $function
	* @param array<mixed> $arguments
	*/
	public static function __callStatic(string $function, array $arguments): string{
		if(isset($arguments[0])){
			return self::Get($function, $arguments[0]);
		}
		else{
			return self::Get($function, $arguments);
		}
	}

	public static function Emit404(): void{
		http_response_code(404);
		include(WEB_ROOT . '/404.php');
		exit();
	}

	public static function RedirectToLogin(bool $redirectToDestination = true, string $destinationUrl = null): void{
		if($redirectToDestination){
			if($destinationUrl === null){
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
		return isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], "Kobo") !== false || strpos($_SERVER['HTTP_USER_AGENT'], "Kindle") !== false);
	}
}
