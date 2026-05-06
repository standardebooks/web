<?
use function Safe\ob_end_clean;
use function Safe\ob_start;

/**
 * A simple templating class that reads directly from PHP files.
 *
 * The `Template` class must extend the `TemplateBase` class. Methods corresponding to each template file are annotated using PHPDoc on the `Template` class.
 *
 * Place template files in `TEMPLATES_PATH`. Calling `Template::MyTemplateFilename(variable: value ...)` will expand passed in variables, execute `TEMPLATES_PATH/MyTemplateFilename.php`, and output the string contents of the executed PHP. For example:
 *
 * ````php
 * <?
 * // Outputs the contents of ``TEMPLATES_PATH`/Header.php`. Inside that file, the variable `$title` will be available.
 * print(Template::Header(title: 'My Title'));
 * ````
 *
 * # Template conventions
 *
 * At the top of each template file, use PHPDoc to define required variables and nullable optional variables that are `null` by default. Next, optional variables with default values are defined with the `$varName ??= $defaultValue;` pattern. For example:
 *
 * ````php
 * <?
 * // TEMPLATES_PATH/Header.php
 *
 * // @var string $title Required.
 * // @var ?string $url Optional but nullable, we define the type of `string` here.
 *
 * $url ??= null; // Optional and nullable. The type was defined in the above PHPDoc, and we set the default as `null` here.
 * $isFeed ??= false; // Optional and not nullable. Both the type and the default value are set here.
 * ?>
 * <?= $title ?>
 * <? if($isFeed){ ?>
 * 	<?= $url ?>
 * <? } ?>
 * ````
 */
abstract class TemplateBase{
	/**
	 * @param array<string, mixed> $arguments
	 */
	public static function __callStatic(string $function, array $arguments): string{
		// Expand the passed variables to make them available to the included template.
		// We use these funny names so that we can use `name` and `value` as template variables if we want to.
		foreach($arguments as $innerName => $innerValue){
			$$innerName = $innerValue;
		}

		ob_start();
		include(TEMPLATES_PATH . '/' . $function . '.php');
		$contents = ob_get_contents() ?: '';
		ob_end_clean();

		return $contents;
	}

	/**
	 * Output the given HTTP code and exit the script. If the request is from a browser, output an error page if it exists for that code.
	 *
	 * @param ?Exception $exception If not `null` and no appropriate error page is found, print the `Exception` using the site header and footer.
	 *
	 * @return never
	 */
	public static function ExitWithCode(Enums\HttpCode $httpCode, ?Exception $exception = null): void{
		http_response_code($httpCode->value);

		if(HttpInput::$RequestType == Enums\HttpRequestType::Browser){
			$filePath = WEB_ROOT . '/' . $httpCode->value . '.php';
			if(file_exists($filePath)){
				/** @phpstan-ignore-next-line */
				include($filePath);
			}
			elseif($exception !== null){
				print(Template::Header(title: $exception->getMessage()));
				print(Template::Error(exception: $exception));
				print(Template::Footer());
			}
		}

		exit();
	}
}
