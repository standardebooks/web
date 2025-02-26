<?
use function Safe\glob;
use function Safe\preg_match_all;

class Manual{
	// *******
	// METHODS
	// *******

	public static function GetLatestVersion(): string{
		$dirs = glob(MANUAL_PATH . '/*', GLOB_ONLYDIR);
		sort($dirs);
		return str_replace(MANUAL_PATH . '/', '', $dirs[sizeof($dirs) - 1]);
	}

	public static function GetRequestedVersion(): ?string{
		try{
			/** @var string $requestUri */
			$requestUri = $_SERVER['REQUEST_URI'];
			if(preg_match_all('|/manual/([0-9]+\.[0-9]+\.[0-9]+)|ius', $requestUri, $matches)){
				return($matches[1][0]);
			}
			else{
				return null;
			}
		}
		catch(Exception){
			return null;
		}
	}
}
