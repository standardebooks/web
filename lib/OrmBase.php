<?
use Safe\DateTime;
use function Safe\substr;

abstract class OrmBase{
	final public function __construct(){
		// Satisfy PHPStan and prevent child classes from having their own constructor
	}

	public static function FillObject(Object $object, array $row): Object{
		foreach($row as $property => $value){
			if(substr($property, strlen($property) - 9) == 'Timestamp'){
				if($value !== null){
					$object->$property = new DateTime($value, new DateTimeZone('UTC'));
				}
				else{
					$object->$property = null;
				}
			}
			elseif(substr($property, strlen($property) - 5) == 'Cache'){
				$property = substr($property, 0, strlen($property) - 5);
				$object->$property = $value;
			}
			else{
				$object->$property = $value;
			}
		}

		return $object;
	}

	public static function FromRow(array $row): Object{
		return self::FillObject(new static(), $row);
	}
}
