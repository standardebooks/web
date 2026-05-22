<?
namespace Traits;

use Safe\DateTimeImmutable;

use function Safe\preg_replace;

trait PropertyFromRequest{
	/**
	 * Given the string name of a property, try to fill it from HTTP data (POST by default).
	 *
	 * Keys in the `$_POST` array must be in the format: `<class-name>-<property-name>`, where `<property-name>` may contain dashes to separate words.
	 *
	 * For example:
	 *
	 * - `$_POST['test-foo-id']` maps to `Test::$FooId`.
	 *
	 * - `$_POST['test-bar']` maps to `Test::$Bar`.
	 *
	 * This function will try to infer the type of the class property using reflection.
	 *
	 * - If a variable doesn't match a class property (either by name or by type), then the class property is unchanged.
	 *
	 * - If a variable matches a class property both by name and type, then the class property is set to the variable.
	 *
	 * - If the class property type is nullable, then a matching but empty variable will set that class property to `null`; this includes `?string` class properties. (I.e., a matching variable of value `""` will set a `?string` class property to `null`.)
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
	 * 	No changes, because `Test::$Foo` is not defined.
	 *
	 * - POST `['test-id' => '123']`:
	 *
	 * 	`Test::$Id` set to `123`.
	 *
	 * - POST `['test-id' => '']`:
	 *
	 * 	`Test::$Id` unchanged, because it is not nullable.
	 *
	 * - POST `['test-name' => 'bob']`:
	 *
	 * 	`Test::$Name` set to `"bob"`.
	 *
	 * - POST `['test-name' => '']`:
	 *
	 * 	`Test::$Name` set to `""` instead of `null`, because it is not nullable.
	 *
	 * - POST `['test-description' => 'abc']`:
	 *
	 * 	`Test::$Description` set to `"abc"`.
	 *
	 * - POST `['test-description' => '']`:
	 *
	 * 	`Test::$Description` set to `null`, because it is nullable.
	 *
	 * - POST `['test-chapter-number' => '456']`:
	 *
	 * 	`Test::$ChapterNumber` set to `456`.
	 *
	 * - POST `['test-chapter-number' => 'abc']`:
	 *
	 * 	`Test::$ChapterNumber` is unchanged, because we are passed a string but the corresponding property is an int.
	 *
	 * - POST `['test-chapter-number' => '']`:
	 *
	 * 	`Test::$ChapterNumber` set to `null`, because an empty string sets nullable properties to `null`.
	 */
	public function PropertyFromRequest(string $property, \Enums\HttpVariableSource $set = \Enums\HttpVariableSource::Body, ?string $httpName = null): void{
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

			$httpVariables = match($set){
				\Enums\HttpVariableSource::QueryString => \Http::$Request->QueryString,
				\Enums\HttpVariableSource::Body => \Http::$Request->Body,
				\Enums\HttpVariableSource::Cookie => \Http::$Request->Cookies,
				\Enums\HttpVariableSource::Session => \Http::$Request->Session
			};

			$rawValue = $httpVariables->Get($httpName, 'empty-string');

			// If the variable was not passed to us, don't change the property.
			if($rawValue === null){
				return;
			}

			$type = $propertyType->getName();
			$isPropertyNullable = $propertyType->allowsNull();
			$isPropertyAnEnum = is_a($type, 'BackedEnum', true);
			$value = null;

			if($isPropertyAnEnum){
				$value = $type::tryFrom($httpVariables->Get($httpName) ?? '');
			}
			else{
				switch($type){
					case 'int':
						$value = $httpVariables->Get($httpName, 'int');
						break;
					case 'bool':
						$value = $httpVariables->Get($httpName, 'bool');
						break;
					case 'float':
						$value = $httpVariables->Get($httpName, 'float');
						break;
					case 'DateTimeImmutable':
					case 'Safe\DateTimeImmutable':
						$value = $httpVariables->Get($httpName, DateTimeImmutable::class);
						break;
					case 'array':
						$value = $httpVariables->Get($httpName, 'array');
						break;
					case 'string':
						$value = $rawValue;
						break;
				}
			}

			if($type == 'string'){
				if($isPropertyNullable){
					if($value == ''){
						$this->{$property} = null;
					}
					else{
						$this->{$property} = $value;
					}
				}
				elseif($value !== null){
					$this->{$property} = $value;
				}
			}
			elseif($isPropertyNullable || $value !== null){
				$this->{$property} = $value;
			}
		}
	}
}
