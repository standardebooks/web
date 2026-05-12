<?
/**
 * Container class for the `OriginatingHttpRequest` for this request.
 */
class Http{
	public static OriginatingHttpRequest $Request;

	public static function Initialize(): void{
		if(!isset(self::$Request)){
			self::$Request = new OriginatingHttpRequest();
		}
	}
}
