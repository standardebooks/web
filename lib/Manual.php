<?
use function Safe\glob;
use function Safe\preg_match_all;
use function Safe\sort;

class Manual{
	public static function GetLatestVersion(): string{
		$dirs = glob(MANUAL_PATH . '/*', GLOB_ONLYDIR);
		sort($dirs);
		return str_replace(MANUAL_PATH . '/', '', $dirs[sizeof($dirs) - 1]);
	}

	public static function GetRequestedVersion(): ?string{
		try{
			if(preg_match_all('|/manual/([0-9]+\.[0-9]+\.[0-9]+)|ius', $_SERVER['REQUEST_URI'], $matches)){
				return($matches[1][0]);
			}
			else{
				return null;
			}
		}
		catch(\Exception $ex){
			return null;
		}
	}
}
