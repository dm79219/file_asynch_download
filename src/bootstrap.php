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

        /* If run from the commandline, DOCUMENT_ROOT will not be set. It is
         * also likely that the ROOT_PATH will not be necessary, so don't
         * bother attempting to figure it out.
         *
         * Secondly, if the directory of main.inc.php is the same as the
         * document root, the the ROOT path truly is '/'
         */
        if(!isset($_SERVER['DOCUMENT_ROOT'])
                || !strcasecmp($_SERVER['DOCUMENT_ROOT'], $dir))
            return '/';

        /* The main idea is to try and use full-path filename of PHP_SELF and
         * SCRIPT_NAME. The SCRIPT_NAME should be the path of that script
         * inside the DOCUMENT_ROOT. This is most likely useful if osTicket
         * is run using something like Apache UserDir setting where the
         * DOCUMENT_ROOT of Apache and the installation path of osTicket
         * have nothing in comon.
         *
         * +---------------------------+-------------------+----------------+
         * | PHP Script                | SCRIPT_NAME       | ROOT_PATH      |
         * +---------------------------+-------------------+----------------+
         * | /home/u1/www/osticket/... | /~u1/osticket/... | /~u1/osticket/ |
         * +---------------------------+-------------------+----------------+
         *
         * The algorithm will remove the directory of main.inc.php from
         * as seen. What's left should be the script executed inside
         * the osTicket installation. That is removed from SCRIPT_NAME.
         * What's left is the ROOT_PATH.
         */
        $bt = debug_backtrace(false);
        $frame = array_pop($bt);
        $file = str_replace('\\','/', $frame['file']);
        $path = substr($file, strlen(ROOT_DIR));
        if($path && ($pos=strpos($_SERVER['SCRIPT_NAME'], $path))!==false)
            return ($pos) ? substr($_SERVER['SCRIPT_NAME'], 0, $pos) : '/';

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
define('CONNECTION_DIR', ROOT_DIR.'connection/');
define('SCRIPT_NUMBER', 2);

define('LOG_FILE', ROOT_DIR.'logs/app_logs.txt');

require_once(ROOT_DIR . 'myAutoLoader.php');
spl_autoload_register('MyAutoLoader::Load');