<?php
/**
*  library.php
*  
*  Divyanshu Mahajan <divyanshumahajan1@gmail.com>
*  
*/
namespace dm79219\asynchLib;
use \dm79219\asynchlib\model as Model;
$s = dirname(__FILE__);
chdir($s);
require_once($s . "/bootstrap.php");
class Library
{
    private $model;
	function __construct()
	{
        $this->model = new Model\FileAsynchModel();
	}

    function run($query,$params=array())
    {
        $result = $this->model->init($query,$params);
        return $result;
    }

    function getDataChunkWise($query,$params,$bucketNumber,$file)
    {
        $this->model->putQuerydataInFile($query,$params,$bucketNumber,$file);
    }

}