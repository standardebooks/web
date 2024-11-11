<?
namespace Traits;

use function Safe\preg_replace;

trait PropertyFromHttp{
	/**
	 * Given the string name of a property, try to fill it from HTTP data (POST by default).
	 *
	 * This function will try to infer the type of the class property using reflection.
	 *
	 * - If a variable doesn't match a class property (either by name or by type), then the class property is unchanged.
	 *
	 * - If a variable matches a class property both by name and type, then the class property is set to the variable.
	 *
	 * - If the class property type is both nullable and not `string` (e.g., the class property is `?int`), then a matching but empty variable will set that class property to `null`; this includes `?string` class properties. (I.e., a matching variable of value `""` will set a `?string` class property to `null`.)
	 *
	 * For example, consider:
	 *
	 * ```
	 * class Test{
	 * 	use Traits\PropertyFromHttp;
	 *
	 * 	public int $Id;
	 * 	public string $Name;
	 * 	public ?string $Description;
	 * 	public ?int $ChapterNumber;
	 * }
	 * ```
	 *
	 * - POST `['test-foo' => 'bar']`:
	 *
	 * 	No changes
	 *
	 * - POST `['test-id' => '123']`:
	 *
	 * 	`$Id`: set to `123`
	 *
	 * - POST `['test-id' => '']`:
	 *
	 * 	`$Id`: unchanged, because it is not nullable
	 *
	 * - POST `['test-name' => 'bob']`:
	 *
	 * 	`$Name`: set to `"bob"`
	 *
	 * - POST `['test-name' => '']`:
	 *
	 * 	`$Name`: set to `""`, because it is not nullable
	 *
	 * - POST `['test-description' => 'abc']`:
	 *
	 * 	`$Description`: set to `abc`
	 *
	 * - POST `['test-description' => '']`:
	 *
	 * 	`$Description`: set to `null`, because it is nullable
	 *
	 * - POST `['test-chapter-number' => '456']`:
	 *
	 * 	`$ChapterNumber`: set to `456`
	 *
	 * - POST `['test-chapter-number' => '']`:
	 *
	 * 	`$ChapterNumber`: set to `null`, because an empty string sets nullable properties to `null`.
	 */
	public function PropertyFromHttp(string $property, \Enums\HttpVariableSource $set = POST, ?string $httpName = null): void{
		try{
			$rp = new \ReflectionProperty($this, $property);
		}
		catch(\ReflectionException){
			return;
		}

		/** @var ?\ReflectionNamedType $propertyType */
		$propertyType = $rp->getType();
		if($propertyType !== null){
			if($httpName === null){
				$httpName = mb_strtolower(preg_replace('/([^^])([A-Z])/u', '\1-\2', $this::class . $property));
			}

			$vars = [];

			switch($set){
				case \Enums\HttpVariableSource::Get:
					$vars = $_GET;
					break;
				case \Enums\HttpVariableSource::Post:
					$vars = $_POST;
					break;
				case \Enums\HttpVariableSource::Cookie:
					$vars = $_COOKIE;
					break;
				case \Enums\HttpVariableSource::Session:
					$vars = $_SESSION;
					break;
			}

			// If the variable was not passed to us, don't change the property.
			if(!isset($vars[$httpName])){
				return;
			}

			$type = $propertyType->getName();
			$isPropertyNullable = $propertyType->allowsNull();
			$isPropertyEnum = is_a($type, 'BackedEnum', true);
			$postValue = null;

			if($isPropertyEnum){
				$postValue = $type::tryFrom(\HttpInput::Str($set, $httpName) ?? '');
			}
			else{
				switch($type){
					case 'int':
						$postValue = \HttpInput::Int($set, $httpName);
						break;
					case 'bool':
						$postValue = \HttpInput::Bool($set, $httpName);
						break;
					case 'float':
						$postValue = \HttpInput::Dec($set, $httpName);
						break;
					case 'DateTimeImmutable':
					case 'Safe\DateTimeImmutable':
						$postValue = \HttpInput::Date($set, $httpName);
						break;
					case 'array':
						$postValue = \HttpInput::Array($set, $httpName);
						break;
					case 'string':
						$postValue = \HttpInput::Str($set, $httpName, true);
						break;
				}
			}

			if($type == 'string'){
				if($isPropertyNullable){
					if($postValue == ''){
						$this->{$property} = null;
					}
					else{
						$this->{$property} = $postValue;
					}
				}
				elseif($postValue !== null){
					$this->{$property} = $postValue;
				}
			}
			elseif($isPropertyNullable || $postValue !== null){
				$this->{$property} = $postValue;
			}
		}
	}
}
