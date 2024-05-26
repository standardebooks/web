<?
use Safe\DateTimeImmutable;
use function Safe\preg_match;
use function Safe\posix_getpwuid;

class DbConnection{
	private \PDO $_link;
	public int $QueryCount = 0;
	public int $LastQueryAffectedRowCount = 0;

	/**
	 * Create a new database connection.
	 *
	 * @param ?string $defaultDatabase The default database to connect to, or `null` not to define one.
	 * @param string $host The database hostname.
	 * @param ?string $user The user to connect to, or `null` to log in as the current Unix user via a local socket.
	 * @param string $password The password to use, or an empty string if no password is required.
	 * @param bool $forceUtf8 If **TRUE**, issue `set names utf8mb4 collate utf8mb4_unicode_ci` when starting the connection.
	 */
	public function __construct(?string $defaultDatabase = null, string $host = 'localhost', ?string $user = null, string $password = '', bool $forceUtf8 = true){
		if($user === null){
			// Get the user running the script for local socket login
			$user = posix_getpwuid(posix_geteuid());
			if($user){
				$user = $user['name'];
			}
		}

		$connectionString = 'mysql:';

		if(stripos($host, ':') !== false){
			$port = null;
			preg_match('/([^:]*):([0-9]+)/ius', $host, $matches);
			$host = $matches[1];
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

		// We can't use persistent connections (connection pooling) because we would have race condition problems with last_insert_id()
		$params = [\PDO::ATTR_EMULATE_PREPARES => false, \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::ATTR_PERSISTENT => false];

		if($forceUtf8){
			$params[\PDO::MYSQL_ATTR_INIT_COMMAND] = 'set names utf8mb4 collate utf8mb4_unicode_ci;';
		}

		$this->_link = new \PDO($connectionString, $user, $password, $params);
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
	 * @return Array<T> An array of objects of type `$class`, or `stdClass` if `$class` is `null`.
	 *
	 * @throws Exceptions\DuplicateDatabaseKeyException When a unique key constraint has been violated.
	 * @throws Exceptions\DatabaseQueryException When an error occurs during execution of the query.
	 */
	public function Query(string $sql, array $params = [], string $class = 'stdClass'): array{
		$handle = $this->PreparePdoHandle($sql, $params);
		$result = [];
		$deadlockRetries = 0;
		$done = false;

		while(!$done){
			try{
				$result = $this->ExecuteQuery($handle, $class);
				$done = true;
			}
			catch(\PDOException $ex){
				if(isset($ex->errorInfo[1]) && $ex->errorInfo[1] == 1213 && $deadlockRetries < 3){
					// InnoDB deadlock, this is normal and happens occasionally. All we have to do is retry the query.
					$deadlockRetries++;
					usleep(500000 * $deadlockRetries); // Give the deadlock some time to clear up.  Start at .5 seconds
				}
				elseif(isset($ex->errorInfo[1]) && $ex->errorInfo[1] == 1062){
					// Duplicate key, bubble this up without logging it so the business logic can handle it
					throw new Exceptions\DuplicateDatabaseKeyException(str_replace('SQLSTATE[23000]: Integrity constraint violation: 1062 ', '', $ex->getMessage() . '. Query: ' . $sql . '. Parameters: ' . vds($params)));
				}
				else{
					throw $this->CreateDetailedException($ex, $sql, $params);
				}
			}
		}

		$this->QueryCount++;

		return $result;
	}

	/**
	 * Execute a select query that returns a join against multiple tables.
	 *
	 * For example, `select * from Users inner join Posts using (UserId)`.
	 *
	 * The result is an array of rows. Each row is an array of objects, with each object containing its columns and values. For example,
	 *
	 * ```
	 * [
	 *	[
	 *		'Users' => {
	 *			'UserId' => 111,
	 *			'Name' => 'Alice'
	 *		},
	 *		'Posts' => {
	 *			'PostId' => 222,
	 *			'Title' => 'Lorem Ipsum'
	 *		},
	 *	],
	 *	[
	 *		'Users' => {
	 *			'UserId' => 333,
	 *			'Name' => 'Bob'
	 *		},
	 *		'Posts' => {
	 *			'PostId' => 444,
	 *			'Title' => 'Dolor sit'
	 *		}
	 *	]
	 *  ]
	 * ```
	 *
	 * **Important note:** When joining against two tables, SQL only returns one column for the join key (typically an ID value). Therefore, if both objects require an ID, the filler method must explicitly assign the ID to one of the two objects that's missing it. The above example shows this behavior: note how we join on `UserId`, but only the `Users` result has the `UserId` column, even though the `Posts` table also has a `UserId` column.
	 *
	 * @template T
	 *
	 * @param string $sql The SQL query to execute.
	 * @param array<mixed> $params An array of parameters to bind to the SQL statement.
	 * @param class-string<T> $class The class to instantiate for each row, or `null` to return an array of rows.
	 *
	 * @return array<T>|array<array<object>> An array of `$class` if `$class` is not `null`, otherwise an array of rows.
	 *
	 * @throws Exceptions\AppException If a class was specified but the class doesn't have a `FromMultiTableRow()` method.
	 * @throws Exceptions\DatabaseQueryException When an error occurs during execution of the query.
	 */
	public function MultiTableSelect(string $sql, array $params = [], ?string $class = null): array{
		if($class !== null && !method_exists($class, 'FromMultiTableRow')){
			throw new Exceptions\AppException('Multi table select attempted, but class ' . $class . ' doesn\'t have a FromMultiTableRow() method.');
		}

		$handle = $this->PreparePdoHandle($sql, $params);
		$result = [];
		$deadlockRetries = 0;
		$done = false;

		while(!$done){
			try{
				$result = $this->ExecuteMultiTableSelect($handle, $class);
				$done = true;
			}
			catch(\PDOException $ex){
				if(isset($ex->errorInfo[1]) && $ex->errorInfo[1] == 1213 && $deadlockRetries < 3){
					// InnoDB deadlock, this is normal and happens occasionally. All we have to do is retry the query.
					$deadlockRetries++;
					usleep(500000 * $deadlockRetries); // Give the deadlock some time to clear up.  Start at .5 seconds
				}
				else{
					throw $this->CreateDetailedException($ex, $sql, $params);
				}
			}
		}

		$this->QueryCount++;

		return $result;
	}

	/**
	 * Given a string of SQL, prepare a PDO handle by binding the parameters to the query.
	 *
	 * @param string $sql The SQL query to execute.
	 * @param array<mixed> $params An array of parameters to bind to the SQL statement.
	 *
	 * @return \PdoStatement The `\PDOStatement` to be used to execute the query.
	 *
	 * @throws Exceptions\DatabaseQueryException When an error occurs during execution of the query.
	 */
	private function PreparePdoHandle(string $sql, array $params): \PDOStatement{
		try{
			$handle = $this->_link->prepare($sql);
		}
		catch(\PDOException $ex){
			throw $this->CreateDetailedException($ex, $sql, $params);
		}

		$name = 0;
		foreach($params as $parameter){
			$name++;

			if($parameter instanceof DateTimeInterface){
				$handle->bindValue($name, $parameter->format('Y-m-d H:i:s'));
			}
			elseif(is_bool($parameter)){
				// MySQL strict mode requires 0 or 1 instead of true or false
				// Can't use PDO::PARAM_BOOL, it just doesn't work

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
	 * @param \PdoStatement $handle The PDO handle to execute.
	 * @param class-string<T> $class The type of object to return in the return array.
	 *
	 * @return array<T> An array of objects of type `$class`, or `stdClass` if `$class` is `null`.
	 *
	 * @throws \PDOException When an error occurs during execution of the query.
	 */
	private function ExecuteQuery(\PDOStatement $handle, string $class = 'stdClass'): array{
		$handle->execute();

		$this->LastQueryAffectedRowCount = $handle->rowCount();

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
							// We'll check for enum types in the class's FromRow() method.
							$object->{$metadata[$i]['name']} = $this->GetColumnValue($row[$i], $metadata[$i]);
						}
						else{
							$object->{$metadata[$i]['name']} = $this->GetColumnValue($row[$i], $metadata[$i], $class);
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
				// HY000 is thrown when there is no result set, e.g. for an update operation.
				// If anything besides that is thrown, then send it up the stack
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
	 * @param \PdoStatement $handle The PDO handle to execute.
	 * @param class-string<T> $class The class to instantiate for each row, or `null` to return an array of rows.
	 *
	 * @return array<T>|array<array<object>> An array of `$class` if `$class` is not `null`, otherwise an array of rows.
	 *
	 * @throws \PDOException When an error occurs during execution of the query.
	 */
	private function ExecuteMultiTableSelect(\PDOStatement $handle, ?string $class): array{
		$handle->execute();

		$this->LastQueryAffectedRowCount = $handle->rowCount();

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
				$object = new stdClass();
				for($i = 0; $i < $handle->columnCount(); $i++){
					if($metadata[$i] === false || !isset($metadata[$i]['table'])){
						continue;
					}

					$object->{$metadata[$i]['name']} = $this->GetColumnValue($row[$i], $metadata[$i]);

					// Don't specify a class, so that we skip enum evaluation. We'll evaluate enums in the class's FromMultiTable function, if any.
					$resultRow[$metadata[$i]['table']] = $object;
				}

				if($class === null){
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
	private function GetColumnValue(mixed $column, array $metadata, string $class = 'stdClass'): mixed{
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
					// We don't check the type VAR_STRING here because in MariaDB, enums are always of type STRING.
					// Since this check is slow, we don't want to run it unnecessarily.
					if($class == 'stdClass'){
						return $column;
					}
					else{
						// If the column is a string and we're filling a typed object, check if the object property is a backed enum. If so, generate it using from(). Otherwise, fill it with a string.
						// Note: Using ReflectionProperty in this way is pretty slow. Maybe we'll think of a
						// better way to automatically fill enum types later.
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
	public function GetLastInsertedId(): int{
		try{
			$id = $this->_link->lastInsertId();
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
	private function CreateDetailedException(\PDOException $ex, string $sql, array $params): Exceptions\DatabaseQueryException{
		// Throw a custom exception that includes more information on the query and paramaters
		return new Exceptions\DatabaseQueryException('Error when executing query: ' . $ex->getMessage() . '. Query: ' . $sql . '. Parameters: ' . vds($params));
	}
}
