<?
class Template{
	protected static $Cache = [];

	protected static function Get(string $templateName, array $arguments = []): string{
		// Expand the passed variables
		// Use these funny names so that we can use 'name' and 'value' as template variables
		foreach($arguments as $innerName => $innerValue){
			$$innerName = $innerValue;
		}

		if(array_key_exists($templateName, self::$Cache)){
			$fileContents = self::$Cache[$templateName];
		}
		else{
			$fileContents = file_get_contents(TEMPLATES_PATH . '/' . $templateName . '.php');
			self::$Cache[$templateName] = $fileContents;
		}

		ob_start();
		eval(' ?>' . $fileContents . '<? ');
		$contents = ob_get_contents() ?: '';
		ob_end_clean();

		return $contents;
	}

	public static function __callStatic(string $function, array $arguments): string{
		if(isset($arguments[0])){
			return self::Get($function, $arguments[0]);
		}
		else{
			return self::Get($function, $arguments);
		}
	}
}
?>
