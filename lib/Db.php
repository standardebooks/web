<?
class Db{
	public static function GetLastInsertedId(): int{
		return $GLOBALS['DbConnection']->GetLastInsertedId();
	}

	public static function GetAffectedRowCount(): int{
		return $GLOBALS['DbConnection']->LastQueryAffectedRowCount;
	}

	/**
	 * @template T
	 * @param string $query
	 * @param array<mixed> $args
	 * @param class-string<T> $class
	 * @return array<T>
	 */
	public static function Query(string $query, array $args = [], string $class = 'stdClass'): array{
		return $GLOBALS['DbConnection']->Query($query, $args, $class);
	}

	/**
	 * Returns a single integer value for the first column database query result.
	 * This is useful for queries that return a single integer as a result, like count(*) or sum(*).
	 * @param string $query
	 * @param array<mixed> $args
	 */
	public static function QueryInt(string $query, array $args = []): int{
		$result = $GLOBALS['DbConnection']->Query($query, $args);

		if(sizeof($result) > 0){
			return current((Array)$result[0]);
		}

		return 0;
	}
}
