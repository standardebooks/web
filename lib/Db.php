<?
use Safe\DateTimeImmutable;
use function Safe\preg_match;
use function Safe\posix_getpwuid;

class Db{
	protected static \PDO $Link; // This is `protected` because we may want to subclass this class to connect to another instance of a database, like Sphinx.
	public static int $QueryCount = 0;
	public static int $LastQueryAffectedRowCount = 0;

	/**
	 * Returns a single integer value for the first column database query result.
	 *
	 * This is useful for queries that return a single integer as a result, like `count(*)` or `sum(*)`.
	 *
	 * @param string $query
	 * @param array<mixed> $args
	 */
	public static function QueryInt(string $query, array $args = []): int{
		$result = static::Query($query, $args);

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
		$result = static::Query($query, $args);

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
		$result = static::Query($query, $args);

		if(sizeof($result) > 0){
			return (bool)current((array)$result[0]);
		}

		return false;
	}

	/**
	 * Returns an SQL query string appropriate for set membership.
	 *
	 * This is useful for queries of the form `WHERE var IN (?,?,?)` and the length of the set is dynamic.
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

	/**
	 * Create a database connection.
	 *
	 * @param ?string $defaultDatabase The default database to connect to, or `null` not to define one.
	 * @param string $host The database hostname.
	 * @param ?string $user The user to connect to, or `null` to log in as the current Unix user via a local socket.
	 * @param string $password The password to use, or an empty string if no password is required.
	 * @param bool $forceUtf8 If **TRUE**, issue `set names utf8mb4 collate utf8mb4_unicode_ci` when starting the connection.
	 */
	public static function Connect(?string $defaultDatabase = null, string $host = 'localhost', ?string $user = null, string $password = '', bool $forceUtf8 = true): void{
		if(isset(static::$Link)){
			return;
		}

		if($user === null){
			// Get the user running the script for local socket login.
			$user = posix_getpwuid(posix_geteuid())['name'];
		}

		$connectionString = 'mysql:';

		if(mb_stripos($host, ':') !== false){
			$port = null;
			preg_match('/([^:]*):([0-9]+)/ius', $host, $matches);
			if(sizeof($matches) > 1){
				$host = $matches[1];
			}
			if(sizeof($matches) > 2){
				$port = $matches[2];
			}

			$connectionString .= 'host=' . $host;

			if($port !== null){
				$connectionString .= ';port=' . $port;
			}
		}
		else{
			$connectionString .= 'host=' . $host;
		}

		if($defaultDatabase !== null){
			$connectionString .= ';dbname=' . $defaultDatabase;
		}

		// We can't use persistent connections (connection pooling) because we would have race conditions with `last_insert_id()`.
		$params = [\PDO::ATTR_EMULATE_PREPARES => false, \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::ATTR_PERSISTENT => false];

		if($forceUtf8){
			$params[\PDO::MYSQL_ATTR_INIT_COMMAND] = 'set names utf8mb4 collate utf8mb4_unicode_ci;';
		}

		static::$Link = new \PDO($connectionString, $user, $password, $params);
	}

	/**
	 * Execute a generic query in the database.
	 *
	 * @template T
	 *
	 * @param string $sql The SQL query to execute.
	 * @param array<mixed> $params An array of parameters to bind to the SQL statement.
	 * @param class-string<T> $class The type of object to return in the return array.
	 *
	 * @return array<T> An array of objects of type `$class`, or `stdClass` if `$class` is `null`.
	 *
	 * @throws Exceptions\DuplicateDatabaseKeyException When a unique key constraint has been violated.
	 * @throws Exceptions\DatabaseQueryException When an error occurs during execution of the query.
	 */
	public static function Query(string $sql, array $params = [], string $class = 'stdClass'): array{
		$handle = static::PreparePdoHandle($sql, $params);
		$result = [];
		$deadlockRetries = 0;
		$done = false;

		while(!$done){
			try{
				$result = static::ExecuteQuery($handle, $class);
				$done = true;
			}
			catch(\PDOException $ex){
				if(isset($ex->errorInfo[1]) && $ex->errorInfo[1] == 1213 && $deadlockRetries < 3){
					// InnoDB deadlock, this is normal and happens occasionally. All we have to do is retry the query.
					$deadlockRetries++;
					usleep(500000 * $deadlockRetries); // Give the deadlock some time to clear up. Start at .5 seconds
				}
				elseif(isset($ex->errorInfo[1]) && $ex->errorInfo[1] == 1062){
					// Duplicate key, bubble this up without logging it so the business logic can handle it
					throw new Exceptions\DuplicateDatabaseKeyException(str_replace('SQLSTATE[23000]: Integrity constraint violation: 1062 ', '', $ex->getMessage() . '. Query: ' . $sql . '. Parameters: ' . vds($params)));
				}
				else{
					throw static::CreateDetailedException($ex, $sql, $params);
				}
			}
		}

		static::$QueryCount++;

		return $result;
	}

	/**
	 * Execute a select query that returns a join against multiple tables.
	 *
	 * For example, `select * from Users inner join Posts on Users.UserId = Posts.UserId`.
	 *
	 * The result is an array of objects generated from the object's `FromMultiTableRow()` method. The `FromMultiTableRow()` method must have this signature:
	 *
	 * ```php
	 * public static function FromMultiTableRow(array $row): T
	 * ```
	 *
	 * **Important note:** If the two tables are joined via `using (Id)` instead of `on TableA.Id = TableB.Id`, the SQL query would return only one column for the join key (`Id` in this case). Therefore, if both objects require that column, the `FromMultiTableRow()` must explicitly assign the column to the object that's missing it, typically the second table in the join.
	 *
	 * @template T
	 *
	 * @param string $sql The SQL query to execute.
	 * @param array<mixed> $params An array of parameters to bind to the SQL statement.
	 * @param class-string<T> $class The class to instantiate for each row.
	 *
	 * @return array<T> An array of `$class`.
	 *
	 * @throws Exceptions\MultiSelectMethodNotFoundException If the class doesn't have a `FromMultiTableRow()` method.
	 * @throws Exceptions\DatabaseQueryException When an error occurs during execution of the query.
	 */
	public static function MultiTableSelect(string $sql, array $params, string $class): array{
		if(!method_exists($class, 'FromMultiTableRow')){
			throw new Exceptions\MultiSelectMethodNotFoundException($class);
		}

		$handle = static::PreparePdoHandle($sql, $params);
		$result = [];
		$deadlockRetries = 0;
		$done = false;

		while(!$done){
			try{
				/** @var array<T> $result */
				$result = static::ExecuteMultiTableSelect($handle, $class);
				$done = true;
			}
			catch(\PDOException $ex){
				if(isset($ex->errorInfo[1]) && $ex->errorInfo[1] == 1213 && $deadlockRetries < 3){
					// InnoDB deadlock, this is normal and happens occasionally. All we have to do is retry the query.
					$deadlockRetries++;
					usleep(500000 * $deadlockRetries); // Give the deadlock some time to clear up. Start at .5 seconds.
				}
				else{
					throw static::CreateDetailedException($ex, $sql, $params);
				}
			}
		}

		static::$QueryCount++;

		return $result;
	}

	/**
	 * Execute a select query that returns a join against multiple tables.
	 *
	 * For example, `select * from Users inner join Posts on Users.UserId = Posts.UserId`.
	 *
	 * The result is an array of rows. Each row is an array of objects, with each object containing its columns and values. For example,
	 *
	 * ```php
	 * [
	 *	[
	 *		'Users' => {
	 *			'UserId' => 111,
	 *			'Name' => 'Alice'
	 *		},
	 *		'Posts' => {
	 *			'PostId' => 222,
	 *			'UserId' => 111,
	 *			'Title' => 'Lorem Ipsum'
	 *		}
	 *	],
	 *	[
	 *		'Users' => {
	 *			'UserId' => 333,
	 *			'Name' => 'Bob'
	 *		},
	 *		'Posts' => {
	 *			'PostId' => 444,
	 *			'UserId' => 333,
	 *			'Title' => 'Dolor sit'
	 *		}
	 *	]
	 *  ]
	 * ```
	 *
	 * **Important note:** If the two tables are joined via `using (Id)` instead of `on TableA.Id = TableB.Id`, the SQL query would return only one column for the join key (`Id` in this case).
	 *
	 * @param string $sql The SQL query to execute.
	 * @param array<mixed> $params An array of parameters to bind to the SQL statement.
	 *
	 * @return array<array<string, stdClass>> An array of `$class` if `$class` is not `null`, otherwise an array of rows of the form `["LeftTableName" => $stdClass, "RightTableName" => $stdClass]`.
	 *
	 * @throws Exceptions\DatabaseQueryException When an error occurs during execution of the query.
	 */
	public static function MultiTableSelectGeneric(string $sql, array $params): array{
		$handle = static::PreparePdoHandle($sql, $params);
		$result = [];
		$deadlockRetries = 0;
		$done = false;

		while(!$done){
			try{
				/** @var array<array<string, stdClass>> $result */
				$result = static::ExecuteMultiTableSelect($handle, stdClass::class);
				$done = true;
			}
			catch(\PDOException $ex){
				if(isset($ex->errorInfo[1]) && $ex->errorInfo[1] == 1213 && $deadlockRetries < 3){
					// InnoDB deadlock, this is normal and happens occasionally. All we have to do is retry the query.
					$deadlockRetries++;
					usleep(500000 * $deadlockRetries); // Give the deadlock some time to clear up. Start at .5 seconds.
				}
				else{
					throw static::CreateDetailedException($ex, $sql, $params);
				}
			}
		}

		static::$QueryCount++;

		return $result;
	}

	/**
	 * Given a string of SQL, prepare a PDO handle by binding the parameters to the query.
	 *
	 * @param string $sql The SQL query to execute.
	 * @param array<mixed> $params An array of parameters to bind to the SQL statement.
	 *
	 * @return \PDOStatement The `\PDOStatement` to be used to execute the query.
	 *
	 * @throws Exceptions\DatabaseQueryException When an error occurs during execution of the query.
	 */
	protected static function PreparePdoHandle(string $sql, array $params): \PDOStatement{
		try{
			/** @throws \PDOException */
			$handle = static::$Link->prepare($sql);
		}
		catch(\PDOException $ex){
			throw static::CreateDetailedException($ex, $sql, $params);
		}

		$name = 0;
		foreach($params as $parameter){
			$name++;

			if($parameter instanceof DateTimeInterface){
				$handle->bindValue($name, $parameter->format('Y-m-d H:i:s'));
			}
			elseif(is_bool($parameter)){
				// MySQL strict mode requires `0` or `1` instead of `true` or `false`.
				// We can't use `PDO::PARAM_BOOL`, it just doesn't work.

				$handle->bindValue($name, $parameter ? 1 : 0, PDO::PARAM_INT);
			}
			elseif($parameter instanceof BackedEnum){
				$handle->bindValue($name, $parameter->value);
			}
			elseif(is_int($parameter)){
				$handle->bindValue($name, $parameter, PDO::PARAM_INT);
			}
			else{
				$handle->bindValue($name, $parameter);
			}
		}

		return $handle;
	}

	/**
	 * Execute a regular query and return the result as an array of objects.
	 *
	 * @template T
	 *
	 * @param \PDOStatement $handle The PDO handle to execute.
	 * @param class-string<T> $class The type of object to return in the return array.
	 *
	 * @return array<T> An array of objects of type `$class`, or `stdClass` if `$class` is `null`.
	 *
	 * @throws \PDOException When an error occurs during execution of the query.
	 */
	protected static function ExecuteQuery(\PDOStatement $handle, string $class = 'stdClass'): array{
		$handle->execute();

		static::$LastQueryAffectedRowCount = $handle->rowCount();

		$result = [];
		do{
			try{
				$columnCount = $handle->columnCount();

				if($columnCount == 0){
					continue;
				}

				$metadata = [];

				for($i = 0; $i < $columnCount; $i++){
					$metadata[$i] = $handle->getColumnMeta($i);
				}

				$rows = $handle->fetchAll(\PDO::FETCH_NUM);

				$useObjectFillMethod = method_exists($class, 'FromRow');

				foreach($rows as $row){
					if($useObjectFillMethod){
						$object = new stdClass();
					}
					else{
						$object = new $class();
					}

					for($i = 0; $i < $handle->columnCount(); $i++){
						if($metadata[$i] === false){
							continue;
						}

						if($useObjectFillMethod){
							// Don't specify a class so that we don't perform an enum check at this point.
							// We'll check for enum types in the class's `FromRow()` method.
							$object->{$metadata[$i]['name']} = static::GetColumnValue($row[$i], $metadata[$i]);
						}
						else{
							$object->{$metadata[$i]['name']} = static::GetColumnValue($row[$i], $metadata[$i], $class);
						}
					}

					if($useObjectFillMethod){
						$result[] = $class::FromRow($object);
					}
					else{
						$result[] = $object;
					}
				}
			}
			catch(\PDOException $ex){
				// `HY000` is thrown when there is no result set, e.g. for an update operation.
				// If anything besides that is thrown, then send it up the stack.
				if(!isset($ex->errorInfo[0]) || $ex->errorInfo[0] != "HY000"){
					throw $ex;
				}
			}
		}
		while($handle->nextRowset());

		return $result;
	}

	/**
	 * Execute a multi-table select query.
	 *
	 * @template T
	 *
	 * @param \PDOStatement $handle The PDO handle to execute.
	 * @param class-string<T> $class The class to instantiate for each row, or `stdClass` to return an array of rows.
	 *
	 * @return array<T>|array<array<string, stdClass>> An array of `$class` if `$class` is not `stdClass`, otherwise an array of rows of the form `["LeftTableName" => $stdClass, "RightTableName" => $stdClass]`.
	 *
	 * @throws \PDOException When an error occurs during execution of the query.
	 */
	protected static function ExecuteMultiTableSelect(\PDOStatement $handle, string $class): array{
		$handle->execute();

		static::$LastQueryAffectedRowCount = $handle->rowCount();

		$result = [];
		do{
			$columnCount = $handle->columnCount();

			if($columnCount == 0){
				continue;
			}

			$metadata = [];

			for($i = 0; $i < $columnCount; $i++){
				$metadata[$i] = $handle->getColumnMeta($i);
			}

			$rows = $handle->fetchAll(\PDO::FETCH_NUM);

			foreach($rows as $row){
				$resultRow = [];
				for($i = 0; $i < $handle->columnCount(); $i++){
					if($metadata[$i] === false || !isset($metadata[$i]['table'])){
						continue;
					}

					$object = $resultRow[$metadata[$i]['table']] ?? new stdClass();

					$object->{$metadata[$i]['name']} = static::GetColumnValue($row[$i], $metadata[$i]);

					$resultRow[$metadata[$i]['table']] = $object;
				}

				if($class == stdClass::class){
					$result[] = $resultRow;
				}
				else{
					$result[] = $class::FromMultiTableRow($resultRow);
				}
			}
		}
		while($handle->nextRowset());

		return $result;
	}

	/**
	 * Given a column value and its database driver metadata, return a strongly-typed value.
	 *
	 * @param mixed $column The value of the column, most likely either a string or integer.
	 * @param array<mixed> $metadata An array of metadata returned from the database driver.
	 * @param string $class The type of object that this return value will be part of.
	 *
	 * @return mixed The strongly-typed column value.
	 */
	protected static function GetColumnValue(mixed $column, array $metadata, string $class = 'stdClass'): mixed{
		if($column === null){
			return null;
		}
		else{
			switch($metadata['native_type'] ?? null){
				case 'DATE':
				case 'DATETIME':
				case 'TIMESTAMP':
					/** @throws void */
					/** @var string $column */
					return new DateTimeImmutable($column);

				case 'LONG':
				case 'TINY':
				case 'SHORT':
				case 'INT24':
				case 'LONGLONG':
					/** @var int $column */
					return intval($column);

				case 'FLOAT':
				case 'DOUBLE':
				case 'NEWDECIMAL':
					/** @var string $column */
					return floatval($column);

				case 'STRING':
					// We don't check the type `VAR_STRING` here because in MariaDB, enums are always of type `STRING`.
					// Since this check is slow, we don't want to run it unnecessarily.
					if($class == 'stdClass'){
						return $column;
					}
					else{
						// If the column is a string and we're filling a typed object, check if the object property is a backed enum. If so, generate it using `type::from()`. Otherwise, fill it with a string.
						// Note: Using `ReflectionProperty` in this way is pretty slow. Maybe we'll think of a better way to automatically fill enum types later.
						try{
							$rp = new ReflectionProperty($class, $metadata['name']);
							/** @var ?ReflectionNamedType $property */
							$property = $rp->getType();
							if($property !== null){
								$type = $property->getName();
								if(is_a($type, 'BackedEnum', true)){
									/** @var string $column */
									return $type::from($column);
								}
								else{
									return $column;
								}
							}
							else{
								return $column;
							}
						}
						catch(\Exception){
							return $column;
						}
					}

				default:
					return $column;
			}
		}
	}

	/**
	 * Get the ID of the last row that was inserted during this database connection.
	 *
	 * @return int The ID of the last row that was inserted during this database connection.
	 *
	 * @throws Exceptions\DatabaseQueryException When the last inserted ID can't be determined.
	 */
	public static function GetLastInsertedId(): int{
		try{
			$id = static::$Link->lastInsertId();
		}
		catch(\PDOException){
			$id = false;
		}

		if($id === false || $id == '0'){
			throw new Exceptions\DatabaseQueryException('Couldn\'t get last insert ID.');
		}
		else{
			return intval($id);
		}
	}

	/**
	 * Create a detailed `Exceptions\DatabaseQueryException` from a `\PDOException`.
	 *
	 * @param \PDOException $ex The exception to create details from.
	 * @param string $sql The prepared SQL that caused the exception.
	 * @param array<mixed> $params The parameters passed to the prepared SQL.
	 *
	 * @return Exceptions\DatabaseQueryException A more detailed exception to be thrown further up the stack.
	 */
	protected static function CreateDetailedException(\PDOException $ex, string $sql, array $params): Exceptions\DatabaseQueryException{
		// Throw a custom exception that includes more information on the query and paramaters.
		return new Exceptions\DatabaseQueryException('Error when executing query: ' . $ex->getMessage() . '. Query: ' . $sql . '. Parameters: ' . vds($params));
	}

	/**
	 * Given a list of arguments to pass to an SQL query whose last two arguments are integers representing the values in a `limit` clause, recalculate the last item to a value close to infinity if that value is zero.
	 *
	 * For example, passing array `['bob', 0, 0]` would return `['bob', 0, 999999999]`. This would be useful to pass to a query with a `limit` clause, like:
	 *
	 * ```sql
	 * select * from Users where Username = ? limit ?, ?;
	 * ```
	 *
	 * @param array<mixed> $args A list of SQL query arguments, with two integers representing limits as the last two items.
	 *
	 * @return array<mixed>
	 */
	public static function ParseLimits(array $args): array{
		if(sizeof($args) >= 2){
			if($args[sizeof($args) - 2] == 0 && ($args[sizeof($args) - 1] === 0 || $args[sizeof($args) - 1] === null)){
				$args[sizeof($args) - 1] = 999999999; // Close enough to infinity.
			}
		}

		return $args;
	}
}
