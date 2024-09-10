<?
namespace Traits;

/**
 * Trait to add getters/setters for class properties.
 *
 * Properties are defined by PHPDoc. Their backing class properties must be either `protected` or `private` and begin with `_`. They are accessed by a protected `Get${Var}` function and set by a protected `Set${Var}` function. For example:
 *
 * ```php
 * // @property string Bar
 * class Foo{
 *     use Traits\Accessor;
 *
 *     protected string $_Bar;
 *
 *     protected function GetBar(): string{
 *         if(!isset($this->_Bar)){
 *             $this->_Bar = 'baz';
 *         }
 *
 *         return $this->_Bar;
 *     }
 *
 *     protected function SetBar(string $value): void{
 *         $this->_Bar = $value;
 *     }
 * }
 *
 * $foo = new Foo();
 * print($foo->Bar); // baz
 * $foo->Bar = 'ipsum';
 * print($foo->Bar); // ipsum
 * ```
 *
 * **Note:** Using the null coalesce `??` operator on an `Accessor` property is equivalent to calling `isset()` and short-circuiting on **`FALSE`**, *instead of calling the getter.*
 *
 * For example, `$object->Tag->Name ?? ''` will always return `""` if `$object->_Tag = <uninitialized>`, without calling the getter first.
 *
 * See <https://reddit.com/r/PHPhelp/comments/x2avqu/anyone_know_the_rules_behind_null_coalescing/>.
 */
trait Accessor{
	public function __get(string $var): mixed{
		$getterFunction = 'Get' . $var;
		$privateVar = '_' . $var;

		if(method_exists($this, $getterFunction)){
			return $this->$getterFunction();
		}

		if(property_exists($this, $privateVar)){
			if(!isset($this->$privateVar) && isset($this->{$var . 'Id'})){
				try{
					$type = (new \ReflectionProperty($this, $privateVar))->getType();

					if($type !== null && ($type instanceof \ReflectionNamedType)){
						$typeName = $type->getName();

						if(method_exists($typeName, 'Get')){
							// If we're asking for a private `_Var` property, and we have a public `VarId` property, and the `Var` class also has a `Var::Get()` method, call that method and return the result.
							$this->$privateVar = $typeName::Get($this->{$var . 'Id'});
						}
					}
				}
				catch(\ReflectionException){
					// Pass, but let through other exceptions that may come from `Var::Get()`.
				}
			}

			return $this->$privateVar;
		}
		else{
			return $this->$var;
		}
	}

	public function __set(string $var, mixed $val): void{
		$setterFunction = 'Set' . $var;
		$privateVar = '_' . $var;

		if(method_exists($this, $setterFunction)){
			$this->$setterFunction($val);
		}
		elseif(property_exists($this, $privateVar)){
			$this->$privateVar = $val;
		}
		else{
			$this->$var = $val;
		}
	}

	public function __unset(string $var): void{
		$unsetterFunction = 'Unset' . $var;
		$privateVar = '_' . $var;

		if(method_exists($this, $unsetterFunction)){
			$this->$unsetterFunction();
		}
		elseif(property_exists($this, $privateVar)){
			unset($this->$privateVar);
		}
	}
}
