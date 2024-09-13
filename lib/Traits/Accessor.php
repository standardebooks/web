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

	/**
	 * Determine if a property is initialized and not `NULL` by first calling its getter.
	 *
	 * Calling `\isset()` on a property will result in the property's getter being called first. This is to facilitate null coalesce chains, for example `$user->Benefits->CanPost ?? false`. `\isset()` short-circuits if the variable is not initialized, so if we didn't call the getter, then it may never get called if the property hasn't been read yet, leading to surprising results.
	 *
	 * `Accessor::isset()` will swallow any exceptions thrown by the getter and return **`FALSE`** instead.
	 *
	 * For example, consider:
	 *
	 * ```
	 * class Test{
	 * 	use Traits\Accessor;
	 *
	 * 	public int $UserId;
	 *
	 * 	protected User $_User;
	 * }
	 *
	 * $t = new Test();
	 *
	 * isset($t->User); // false
	 *
	 * $t->UserId = 1; // This User exists.
	 *
	 * isset($t->User); // true
	 *
	 * $t = new Test();
	 *
	 * $t->UserId = 100; // This User does not exist.
	 *
	 * isset($t->User); // false
	 *
	 * $t->User = new User();
	 *
	 * isset($t->User); // true
	 *
	 * @see <https://reddit.com/r/PHPhelp/comments/x2avqu/anyone_know_the_rules_behind_null_coalescing/>
	 */
	public function __isset(string $var): bool{
		$privateVar = '_' . $var;

		if(property_exists($this, $privateVar)){
			try{
				$this->__get($var);
			}
			catch(\Throwable){
				// Pass.
			}

			return isset($this->$privateVar);
		}

		return false;
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
