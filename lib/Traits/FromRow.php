<?
namespace Traits;

/**
 * Normally, the `DbConnection` class fills in an object itself, using reflection to decide on enums. Sometimes, we want to define an explicit `FromRow()` method on a class. This trait provides a default `FromRow()` method that assigns columns to object properties, and attemps to figure out enum types. The object can override this method if necessary.
 */
trait FromRow{
	/**
	 * Fill an object with the given database row.
	 * This is useful when a subclass has to fill its object in a `FromRow()` method, but its parent class also has a `FromRow()` method defined. For example, `CompoundAction` and `Action`.
	 *
	 * @template T of self
	 *
	 * @param T $object
	 * @return T
	 */
	public static function FillObject(object $object, \stdClass $row): object{
		foreach(get_object_vars($row) as $property => $value){
			if(is_string($value)){
				try{
					$type = (new \ReflectionProperty($object, $property))->getType();
					if($type instanceof \ReflectionNamedType){
						$typeName = $type->getName();
						if($typeName != 'string' && is_a($typeName, 'BackedEnum', true)){
							$object->$property = $typeName::from($value);
						}
						else{
							$object->$property = $value;
						}
					}


				}
				catch(\Exception){
					$object->$property = $value;
				}
			}
			else{
				$object->$property = $value;
			}
		}

		return $object;
	}

	/**
	 * Return an object based on a database row result. This is a fallback object filler for when the parent class hasn't defined their own.
	 */
	public static function FromRow(\stdClass $row): static{
		return self::FillObject(new static(), $row);
	}
}
