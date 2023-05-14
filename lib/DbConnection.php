<?
use Safe\DateTime;
use function Safe\preg_match;

class DbConnection{
	private $_link = null;
	public $IsConnected = false;
	public $QueryCount = 0;
	public $LastQueryAffectedRowCount = 0;

	public function __construct(?string $defaultDatabase = null, string $host = 'localhost', ?string $user = null, string$password = '', bool $forceUtf8 = true, bool $require = true){
		if($user === null){
			// Get the user running the script for local socket login
			$user = posix_getpwuid(posix_geteuid());
			if($user){
				$user = $user['name'];
			}
		}

		$connectionString = 'mysql:';

		try{
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

			$params = [\PDO::ATTR_EMULATE_PREPARES => false, \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::ATTR_PERSISTENT => false];

			if($forceUtf8){
				$params[\PDO::MYSQL_ATTR_INIT_COMMAND] = 'set names utf8mb4 collate utf8mb4_unicode_ci;';
			}

			// We can't use persistent connections (connection pooling) because we would have race condition problems with last_insert_id()
			$this->_link = new \PDO($connectionString, $user, $password, $params);

			$this->IsConnected = true;
		}
		catch(Exception $ex){
			if(SITE_STATUS == SITE_STATUS_DEV){
				var_dump($ex);
			}
			else{
				Log::WriteErrorLogEntry('Error connecting to ' . $connectionString . '. Exception: ' . vds($ex));
			}

			if($require){
				print("Something crazy happened in our database.  <a href=\"/contact/\">Drop us a line</a> and let us know exactly what you were doing and we'll take a look.\n<br />The more detail you can provide, the better!");
				exit();
			}
		}
	}

	// Inputs:	string 	$sql 		= the SQL query to execute
	//		array 	$params 	= an array of parameters to bind to the SQL statement
	// Returns:	a resource record or null on error
	/**
	* @param string $sql
	* @param array<mixed> $params
	* @param string $class
	* @return Array<mixed>
	*/
	public function Query(string $sql, array $params = [], string $class = 'stdClass'): array{
		if(!$this->IsConnected){
			return [];
		}

		$this->QueryCount++;
		$result = [];
		$preparedSql = $sql;

		$handle = $this->_link->prepare($preparedSql);
		if(!is_array($params)){
			$params = [$params];
		}

		$name = 0;
		foreach($params as $parameter){
			$name++;

			if(is_a($parameter, 'DateTime') || is_a($parameter, 'DateTimeImmutable')){
				$parameter = $parameter->format('Y-m-d H:i:s');
			}

			// MySQL strict mode requires 0 or 1 instead of true or false
			// Can't use PDO::PARAM_BOOL, it just doesn't work
			if(is_bool($parameter)){
				if($parameter){
					$parameter = 1;
				}
				else{
					$parameter = 0;
				}
			}

			if(is_int($parameter)){
				$handle->bindValue($name, $parameter, PDO::PARAM_INT);
			}
			else{
				$handle->bindValue($name, $parameter);
			}
		}

		$deadlockRetries = 0;
		$done = false;
		while(!$done){
			try{
				$result = $this->ExecuteQuery($handle, $class);
				$done = true;
			}
			catch(\PDOException $ex){
				if($ex->errorInfo[1] == 1213 && $deadlockRetries < 3){ // InnoDB deadlock, this is normal and happens occasionally.  All we have to do is retry the query.
					$deadlockRetries++;

					usleep(500000 * $deadlockRetries); // Give the deadlock some time to clear up.   Start at .5 seconds
				}
				elseif($ex->getCode() == '23000'){
					// Duplicate key, bubble this up without logging it so the business logic can handle it
					throw($ex);
				}
				else{
					$done = true;
					if(SITE_STATUS == SITE_STATUS_DEV){
						throw($ex);
					}
					else{
						Log::WriteErrorLogEntry($ex->getMessage());
						Log::WriteErrorLogEntry($preparedSql);
						Log::WriteErrorLogEntry(vds($params));
						throw($ex);
					}
				}
			}
		}

		return $result;
	}

	/**
	* @return Array<mixed>
	*/
	private function ExecuteQuery(PDOStatement $handle, string $class = 'stdClass'): array{
		$handle->execute();

		$this->LastQueryAffectedRowCount = $handle->rowCount();

		$result = [];
		do{
			try{
				$columnCount = $handle->columnCount();
				if($columnCount > 0){

					$metadata = [];

					for($i = 0; $i < $columnCount; $i++){
						$metadata[$i] = $handle->getColumnMeta($i);
						if($metadata[$i] && preg_match('/^(Is|Has|Can)[A-Z]/u', $metadata[$i]['name']) === 1){
							// MySQL doesn't have a native boolean type, so fake it here if the column
							// name starts with Is, Has, or Can and is followed by an uppercase letter
							$metadata[$i]['native_type'] = 'BOOL';
						}
					}

					$rows = $handle->fetchAll(\PDO::FETCH_NUM);

					if(!is_array($rows)){
						continue;
					}

					foreach($rows as $row){
						$object = new $class();

						for($i = 0; $i < $handle->columnCount(); $i++){
							if($row[$i] === null){
								$object->{$metadata[$i]['name']} = null;
							}
							else{
								switch($metadata[$i]['native_type']){
									case 'DATETIME':
										$object->{$metadata[$i]['name']} = new DateTime($row[$i], new DateTimeZone('UTC'));
										break;

									case 'LONG':
									case 'TINY':
									case 'SHORT':
									case 'INT24':
									case 'LONGLONG':
										$object->{$metadata[$i]['name']} = intval($row[$i]);
										break;

									case 'FLOAT':
									case 'DOUBLE':
										$object->{$metadata[$i]['name']} = floatval($row[$i]);
										break;

									case 'BOOL':
										$object->{$metadata[$i]['name']} = $row[$i] == 1 ? true : false;
										break;

									default:
										$object->{$metadata[$i]['name']} = $row[$i];
										break;
								}
							}
						}

						$result[] = $object;
					}
				}
			}
			catch(\PDOException $ex){
				// HY000 is thrown when there is no result set, e.g. for an update operation.
				// If anything besides that is thrown, then send it up the stack
				if($ex->errorInfo[0] != "HY000"){
					throw $ex;
				}
			}
		}while($handle->nextRowset());

		return $result;
	}

	// Gets the last auto-increment id
	public function GetLastInsertedId(): ?int{
		$id = $this->_link->lastInsertId();

		if($id == 0){
			return null;
		}

		return $id;
	}
}
