<?
use function Safe\file_get_contents;
use function Safe\ob_end_clean;

class Template{
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

	public static function __callStatic(string $function, array $arguments): string{
		if(isset($arguments[0])){
			return self::Get($function, $arguments[0]);
		}
		else{
			return self::Get($function, $arguments);
		}
	}
}
