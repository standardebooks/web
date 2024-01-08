<?
class Db{
	public static function GetLastInsertedId(): int{
		return $GLOBALS['DbConnection']->GetLastInsertedId();
	}

	public static function GetAffectedRowCount(): int{
		return $GLOBALS['DbConnection']->LastQueryAffectedRowCount;
	}

	/**
	* @param string $query
	* @param array<mixed> $args
	* @param string $class
	* @return Array<mixed>
	*/
	public static function Query(string $query, array $args = [], string $class = 'stdClass'): array{
		if(!isset($GLOBALS['DbConnection'])){
			$GLOBALS['DbConnection'] = new DbConnection(DATABASE_DEFAULT_DATABASE, DATABASE_DEFAULT_HOST);
		}

		if(!is_array($args)){
			$args = [$args];
		}

		return $GLOBALS['DbConnection']->Query($query, $args, $class);
	}

	/**
	* @param string $query
	* @param array<mixed> $args
	*/
	public static function QueryInt(string $query, array $args = []): int{
		// Useful for queries that return a single integer as a result, like count(*) or sum(*).

		if(!isset($GLOBALS['DbConnection'])){
			$GLOBALS['DbConnection'] = new DbConnection(DATABASE_DEFAULT_DATABASE, DATABASE_DEFAULT_HOST);
		}

		if(!is_array($args)){
			$args = [$args];
		}

		$result = $GLOBALS['DbConnection']->Query($query, $args);

		if(sizeof($result) > 0){
			return current((Array)$result[0]);
		}

		return 0;
	}
}
