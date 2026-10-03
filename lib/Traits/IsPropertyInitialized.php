<?
namespace Traits;

trait IsPropertyInitialized{
	/**
	 * Return whether the named property's backing value has been initialized without invoking its getter; if the property doesn't exist, returns **`FALSE`**.
	 */
	public function IsPropertyInitialized(string $propertyName): bool{
		try{
			return (new \ReflectionProperty($this, $propertyName))->isInitialized($this);
		}
		catch(\ReflectionException){
			return false;
		}
	}
}
