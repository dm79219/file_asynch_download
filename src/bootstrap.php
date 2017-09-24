<?php
/**
* bootstrap.php
*  
*  Divyanshu Mahajan <divyanshumahajan1@gmail.com>
*  vim: expandtab sw=4 ts=4 sts=4:
*/

//namespace dm79219\asynchLib;

class Bootstrap
{
	
	function __construct()
	{
		# code...
	}

	static function get_root_path($dir) {

        
        //if(!isset($_SERVER['DOCUMENT_ROOT']) || !strcasecmp($_SERVER['DOCUMENT_ROOT'], $dir))
          //  return '/';

        $a = getcwd();
        $bt = debug_backtrace();

        $bt_path = $bt[0]['args'][0];
        $root_path = explode($a,$bt_path)[1];
        if($root_path)
        return $root_path;
        if (self::is_cli())
            return '/';

        return null;

    }

    /* returns true if script is being executed via commandline */
    function is_cli() {
        return (!strcasecmp(substr(php_sapi_name(), 0, 3), 'cli')
                || (!isset($_SERVER['REQUEST_METHOD']) &&
                    !isset($_SERVER['HTTP_HOST']))
                    //Fallback when php-cgi binary is used via cli
                );
    }

}
$dir = __DIR__;
define('ROOT_DIR', $dir.'/');
$filename = basename(__FILE__);
if (!defined('ROOT_PATH') && ($rp = Bootstrap::get_root_path(dirname(__file__))))
    define('ROOT_PATH', rtrim($rp, '/') . '/');
define('FILE_NAME', $filename);
define('BUCKET_SIZE', 1000);
define('FILE_CHUNK_PATH', ROOT_PATH.'upload/');
define('FILE_CHUNK_DIR', ROOT_DIR.'upload/');
define('HELPERS_PATH', ROOT_PATH.'helpers/');
define('HELPERS_DIR', ROOT_DIR.'helpers/');
define('MODEL_DIR', ROOT_DIR.'model/');
define('DB_CONNECTION_DIR', ROOT_DIR.'db_connection/');
define('SCRIPT_NUMBER', 2);

define('LOG_FILE', ROOT_DIR.'logs/app_logs.txt');

require_once(ROOT_DIR . 'myAutoLoader.php');
spl_autoload_register('MyAutoLoader::Load');