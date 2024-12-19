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
	 * Execute a select query that returns a join against multiple tables.
	 *
	 * @template T
	 *
	 * @param string $sql The SQL query to execute.
	 * @param array<mixed> $params An array of parameters to bind to the SQL statement.
	 * @param class-string<T> $class The class to instantiate for each row, or `null` to return an array of rows.
	 *
	 * @return array<T> An array of `$class`.
	 *
	 * @throws Exceptions\MultiSelectMethodNotFoundException If a class was specified but the class doesn't have a `FromMultiTableRow()` method.
	 * @throws Exceptions\DatabaseQueryException When an error occurs during execution of the query.
	 */
	public static function MultiTableSelect(string $sql, array $params, string $class): array{
		/** @throws Exceptions\DatabaseQueryException|Exceptions\MultiSelectMethodNotFoundException */
		return $GLOBALS['DbConnection']->MultiTableSelect($sql, $params, $class);
	}

	/**
	 * Returns a single integer value for the first column database query result.
	 *
	 * This is useful for queries that return a single integer as a result, like `count(*)` or `sum(*)`.
	 *
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

	/**
	 * Returns a single float value for the first column database query result.
	 *
	 * This is useful for queries that return a single float as a result, like `avg(*)` or `sum(*)`.
	 *
	 * @param string $query
	 * @param array<mixed> $args
	 */
	public static function QueryFloat(string $query, array $args = []): float{
		$result = $GLOBALS['DbConnection']->Query($query, $args);

		if(sizeof($result) > 0){
			return current((Array)$result[0]);
		}

		return 0;
	}

	/**
	 * Returns a single boolean value for the first column database query result.
	 *
	 * This is useful for queries that return a boolean as a result, like `select exists()`.
	 *
	 * @param string $query
	 * @param array<mixed> $args
	 */
	public static function QueryBool(string $query, array $args = []): bool{
		$result = $GLOBALS['DbConnection']->Query($query, $args);

		if(sizeof($result) > 0){
			return (bool)current((array)$result[0]);
		}

		return false;
	}

	/**
	 * Returns an SQL query string appropriate for set membership.
	 *
	 * This is useful for queries of the form WHERE var IN (?,?,?) and the length of the set is dynamic.
	 *
	 * @param array<mixed> $arr
	 */
	public static function CreateSetSql(array $arr): string{
		$sql = '(';

		for($i = 0; $i < sizeof($arr); $i++){
			$sql .= '?,';
		}

		return rtrim($sql, ',') . ')';
	}
}
