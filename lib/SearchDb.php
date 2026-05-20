<?
/**
 * Provides a PDO connection for SQL queries against an external search database.
 */
class SearchDb extends Db{
	// Redefine these so we don't override the superclass definitions.
	protected static \PDO $Link;
	public static int $QueryCount = 0;
	public static int $LastQueryAffectedRowCount = 0;

	/**
	 * Create a search database connection.
	 *
	 * @param ?string $defaultDatabase Unused.
	 * @param string $host Unused.
	 * @param ?string $user Unused.
	 * @param string $password Unused.
	 * @param bool $forceUtf8 Unused.
	 * @param bool $emulatePrepares Unused.
	 */
	public static function Connect(?string $defaultDatabase = null, string $host = DATABASE_SEARCH_HOST, ?string $user = null, string $password = '', bool $forceUtf8 = false, bool $emulatePrepares = false): void{
		// We have to set `$emulatePrepares` to **`TRUE`** otherwise `show meta` will fail with a wire protocol error.
		parent::Connect(null, DATABASE_SEARCH_HOST . ':' . DATABASE_SEARCH_PORT, '', '', false, true);
	}

	/**
	 * Escape a string for use as a paremeter in the `match()` function.
	 *
	 * The `match()` function accepts certain search operators, like `"` or `/`, which must be escaped before being passed to `match()`. Otherwise, they may cause syntax errors in the search language (not SQL). This is a separate concept from escaping SQL parameters at the PDO level.
	 */
	public static function EscapeMatch(string $string): string{
		return strtr($string, [
			'\\' => '\\\\',
			'!' => '\\!',
			'"' => '\\"',
			"'" => "\\'",
			'$' => '\\$',
			'(' => '\\(',
			')' => '\\)',
			'-' => '\\-',
			'/' => '\\/',
			'<' => '\\<',
			'@' => '\\@',
			'^' => '\\^',
			'|' => '\\|',
			'~' => '\\~',
			'[' => '\\[',
			']' => '\\]',
			'=' => '\\=',
			'*' => '\\*',
			'?' => '\\?',
			'%' => '\\%',
			'{' => '\\{',
			'}' => '\\}',
			'.' => '\\.',
			':' => '\\:',
		]);
	}

	/**
	 * Execute a generic query in the database. Creates a connection if one doesn't exist yet.
	 *
	 * @template T
	 *
	 * @param string $sql The SQL query to execute.
	 * @param array<mixed> $params An array of parameters to bind to the SQL statement.
	 * @param class-string<T> $class The type of object to return in the return array.
	 *
	 * @return array<T> An array of objects of type `$class`, or `stdClass` if `$class` is `null`.
	 *
	 * @throws Exceptions\DuplicateDatabaseKeyException If a unique key constraint has been violated.
	 * @throws Exceptions\DatabaseQueryException If an error occurs during execution of the query.
	 * @throws Exceptions\SearchSyntaxInvalidException If the syntax of a search query used in the `match()` function was invalid.
	 */
	public static function Query(string $sql, array $params = [], string $class = 'stdClass'): array{
		if(!isset(static::$Link)){
			try{
				static::Connect();
			}
			catch(Exceptions\DatabaseConnectionFailedException){
				// Log the failure but return an empty rowset.
				$log = new Log();
				$log->Write('Failed to conenct to search database.');
				return [];
			}
		}

		try{
			for($i = 0; $i < sizeof($params); $i++){
				if($params[$i] instanceof DateTimeInterface){
					// Manticore doesn't accept the usual SQL datetime string format, so send a Unix timestamp instead.
					$params[$i] = $params[$i]->getTimestamp();
				}
			}

			$result = parent::Query($sql, $params, $class);
		}
		catch(Exceptions\DatabaseQueryException $ex){
			if(strpos($ex->getMessage(), 'P08: syntax error') !== false){
				throw new Exceptions\SearchSyntaxInvalidException();
			}
			else{
				throw $ex;
			}
		}

		return $result;
	}

	/**
	 * Execute a query in the database. If the query contains a `match()` function, and the parameter passed to that function contains invalid search syntax, then retry the query with the parameter escaped.
	 *
	 * @template T
	 *
	 * @param string $sql The SQL query to execute.
	 * @param array<mixed> $params An array of parameters to bind to the SQL statement.
	 * @param int $matchParamIndex The 0-based index of the parameter in the `$params` argument that is passed to the `match()` function in the query.
	 * @param class-string<T> $class The type of object to return in the return array.
	 *
	 * @return array<T> An array of objects of type `$class`, or `stdClass` if `$class` is `null`.
	 *
	 * @throws Exceptions\DuplicateDatabaseKeyException If a unique key constraint has been violated.
	 * @throws Exceptions\DatabaseQueryException If an error occurs during execution of the query.
	 * @throws Exceptions\SearchSyntaxInvalidException If the syntax of a search query used in the `match()` function was invalid.
	 */
	public static function QueryMatch(string $sql, array $params = [], int $matchParamIndex = 0, string $class = 'stdClass'): array{
		try{
			$result = static::Query($sql, $params, $class);
		}
		catch(Exceptions\DatabaseQueryException){
			// There was a syntax error in the `match()` function's search language (e.g. there was an opening `"` but no closing `"`); escape all search operators and try again.
			if(isset($params[$matchParamIndex])){
				/** @var string $query */
				$query = $params[$matchParamIndex];
				$params[$matchParamIndex] = static::EscapeMatch($query);
				$result = static::Query($sql, $params, $class);
			}
			else{
				$result = [];
			}
		}

		return $result;
	}

	/**
	 * Try to get the number of *total* results for the last run query using query metadata; for example, a query with a `limit` clause may return 10 results, and have 1,000 *total* results.
	 *
	 * @see <https://manual.manticoresearch.com/Node_info_and_management/SHOW_META>
	 *
	 * @return ?int The number of total results, or `null` if the number can't be determined.
	 */
	public static function GetLastQueryTotalResultCount(): ?int{
		try{
			// N.B.: escaped *single* quotes required.
			$metaResult = static::Query('SHOW meta like \'total%\'');
			$totalRelation = null;
			$totalFound = null;

			foreach($metaResult as $row){
				if($row->Variable_name == 'total_relation'){
					$totalRelation = (string)$row->Value;
				}
				if($row->Variable_name == 'total_found'){
					$totalFound = (int)$row->Value;
				}
			}

			if($totalRelation == 'eq' and $totalFound !== null){
				// If we returned the exact number of total matches, use that.
				return $totalFound;
			}

			return null;
		}
		catch(\Exception){
			return null;
		}
	}
}
