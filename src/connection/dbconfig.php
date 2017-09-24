<?php

/**
* 
*/

namespace dm79219\asynchlib\connection;
class DbConfig
{

	private $slave_db_config = array(
									"db_type" => "mysql",
									"db_host" => "localhost",
									"db_name" => "osticket3",
									"db_user" => "root",
									"db_pass" => "12345",
									"db_charset" => "utf8",
									"db_port" => "3306"
								);
	private $mater_db_config = array(
									"db_type" => "mysql",
									"db_host" => "localhost",
									"db_name" => "osticket3",
									"db_user" => "root",
									"db_pass" => "12345",
									"db_charset" => "utf8",
									"db_port" => "3306"
								);
	private $query_write_types = array('insert', 'update', 'delete', 'replace', 'set');
	private $query_read_types = array('select', 'show', 'explain');

	function __construct($type="slave")
	{
		$this->type = $type;
	}

	function Load()
	{
		if($this->type == "slave")
			return $this->slave_db_config;
		else if($type == "master")
			return $this->master_db_config;
	}

	function defineQueryType()
	{
		$type = array("write" => serialize($this->query_write_types), "read" => serialize($this->query_read_types));
		return $type;
	}
}

// define('SLAVE_DBTYPE','mysql');
// define('SLAVE_DBHOST','localhost');
// define('SLAVE_DBNAME','osticket3');
// define('SLAVE_DBUSER','root');
// define('SLAVE_DBPASS','12345');
// define('SLAVE_CHARSET', 'utf8');
// define('SLAVE_DBPORT','3306');


// // Write Query types...
// define('OST_QUERY_WRITE_TYPES', serialize(array('insert', 'update', 'delete', 'replace', 'set')));

// //Read Query types
// define('OST_QUERY_READ_TYPES', serialize(array('select', 'show', 'explain')));

