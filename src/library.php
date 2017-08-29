<?php
/**
*  library.php
*  
*  Divyanshu Mahajan <divyanshumahajan1@gmail.com>
*  
*/

//namespace dm79219\asynchLib;
//use \dm79219\asynchlib\model as Model;

require_once("bootstrap.php");
class Library
{
    private $model;
	function __construct()
	{
        $this->model = new FileAsynchModel();
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