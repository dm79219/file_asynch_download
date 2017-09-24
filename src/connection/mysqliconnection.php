<?php

/**
* 
*/

namespace dm79219\asynchlib\connection;
use \PDO;

class MysqliConnection
{
	private static $databaseObj;
	private $pdoObj;
	private $dbConfigObj;
	private function __construct()
	{
		$this->dbConfigObj = new DbConfig();
		
		$this->db_connect();
		//$this->setDbAttributes();
	}

	public static function getObject()
	{
		
		if(!self::$databaseObj)
		{
			self::$databaseObj = new MysqliConnection();
		}
		return self::$databaseObj;
	}

	function _db_get_query_type(){
		$type = $this->dbConfigObj->defineQueryType();
		$query_type = strtolower(current(explode(' ', $query)));
		$write_types_array = unserialize($type['write']);
		$read_types_array = unserialize($type['read']);

		if (in_array($query_type, $write_types_array)) {
			return 'write';
		}
		elseif (in_array($query_type, $read_types_array)) {
			{
				if(strpos(strtolower($query), 'for update')!==false){
					return 'write';
				}
				return 'read';
			}
		}
	}

	function db_connect(){
		try{
			$db_config = $this->dbConfigObj->Load();
			$this->pdoObj = new PDO($db_config['db_type'].':host='.$db_config['db_host'].';dbname='.$db_config['db_name'], $db_config['db_user'], $db_config['db_pass']);
		    $this->pdoObj->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdoObj->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
            $this->pdoObj->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES UTF8');
		}
		catch(PDOException $e)
		{
			$logData["Title"] = "PDO Exception";
			$logData["Message"] = $e->getMessage();
			$logData["Type"] = "Error";
			$logData["File"] = "mysqli.php";
			$logData["error_name"] = "PDO Connection error";
			$this->logDatabaseErrors($logData);
			die;
		}
	}

	// function prepareQuery($query, $params){
	// 	$needle = '?:';
	// 	$positions = array();
	// 	$positions = StringClass::find_all_occurences($query,$needle);
	// 	foreach ($params as $var) {
	// 		$query = StringClass::str_replace_first($needle, $var, $query);
	// 	}

	// }

	// function executeQuery($query, $bucketNumber){
	// 	$bucket_size = FILE_CHUNK_PATH;
	// 	$offset = $bucketNumber * FILE_CHUNK_PATH;
	// 	$query.= " limit ". $offset .", ". $bucket_size; 

	// }

	public function getConnection(){
		return $this->pdoObj;
	}

	public function prepareStatement($sql)
	{
		$stmt = $this->pdoObj->prepare($sql);
		return $stmt;
	}

	public function getParamType($param)
	{
		switch (true) {
			case is_int($param):
				$type = PDO::PARAM_INT;
				break;
			case is_bool($param):
				$type = PDO::PARAM_BOOL;
				break;
			case is_null($param):
				$type = PDO::PARAM_NULL;
				break;
			default:
				$type = PDO::PARAM_STR;
				break;
		}
		return $type;
	}

	private function _bindParam($stmt, $params)
	{
		if(!is_array($params))
		{
			return;
		}
		foreach ($params as $key => $value) {
			$type = $this->getParamType($value);
			$stmt->bindParam($key,$value,$type);
		}
		return $stmt;
	}

	public function executeStatement($stmt)
	{
		$result = '';
		try
		{
			$result = $stmt->execute();
		}
		catch(PDOException $e)
		{
			$errorArr = $stmt->errorInfo();
			$logData["Title"] = "PDO Exception";
			$logData["Message"] = $e->getMessage();
			$logData["Type"] = "Error";
			$logData["Driver err code"] = $errorArr[1];
			$logData["Driver err msg"] = $errorArr[2];
			$logData["File"] = "mysqli.php";
			$logData["error_name"] = "PDO Query error";
			$this->logDatabaseErrors($logData);
			die("DB Error");
		}
		return $stmt;
	}

	public function fetchRecords($stmt)
	{
		$output = array();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
                  	$output[] = $row;

		return $output;
	}

	public function getLastInsertedId()
	{
		return $this->pdoObj->lastInsertId();
	}

	public function getAffectedRows($stmt)
	{
		return $stmt->rowCount();
	}

	private function setDbAttributes()
	{
		$sql = "SET NAMES UTF8";
		$stmt = $this->prepareStatement($sql);
		$this->executeStatement($stmt);

		$sql = "SET SESSION group_concat_max_len = 3000";
		$stmt = $this->prepareStatement($sql);
		$this->executeStatement($stmt);
	
		$sql = "set @@sql_mode = ''";	
		$stmt = $this->prepareStatement($sql);
		$this->executeStatement($stmt);
	}

	function db_query($sql,$params = array())
	{
		$stmt = $this->prepareStatement($sql);
		if(is_array($params) && !empty($params))
			$stmt = $this->_bindParam($stmt,$params);
		if($stmt)
			$result = $this->executeStatement($stmt);
		return $result;
	}

	public function logDatabaseErrors($data)
	{
		$data = json_encode($data);
		$fileObj = new File(LOG_FILE,'w');
		$fileObj->filePuts($data);
		$fileObj->close();
	}

	public function closeConnection()
	{
		$this->pdoObj = null;
		self::$databaseObj[$this->dbcName] = null;
	}

}