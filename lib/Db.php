<?

class Db{
	public static function GetLastInsertedId(){
		return $GLOBALS['DbConnection']->GetLastInsertedId();
	}

	public static function Query(string $query, $args = []){
		if(!is_array($args)){
			$args = [$args];
		}

		return $GLOBALS['DbConnection']->Query($query, $args);
	}
}
