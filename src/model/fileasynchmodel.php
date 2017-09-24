<?php
/**
* 
*/
namespace dm79219\asynchlib\model;
use \dm79219\asynchlib\connection as Connection;
use \dm79219\asynchlib\helpers as Helper;


class FileAsynchModel
{
	private $db_conn;
	private $no_of_scripts;
	private $file_dir;
	private $file_dir_path;
	function __construct()
	{
		$this->db_conn = Connection\MysqliConnection::getObject();
		$this->no_of_scripts = SCRIPT_NUMBER;
		$time = time();
		$this->file_dir = FILE_CHUNK_DIR.$time."/";
		$this->file_dir_path = FILE_CHUNK_PATH.$time."/";
	}

	function getData($sql,$params=array()){
		$res = $this->db_conn->db_query($sql,$params);
		$output = $this->db_conn->fetchRecords($res);
		return $output;
	}

	public function init($query,$params=array())
	{
        $bucketNumber = 0;
        $shellObj = new Helper\ShellCommand();
        $shellObj->make_dir($this->file_dir);
        $i=1;
        while(1){
            for($i=1;$i< $this->no_of_scripts;$i++){
                $file_path = $this->file_dir.$bucketNumber.".csv";
                $cmd = "php ".ROOT_DIR."scripts.php ".escapeshellarg($query)." ".escapeshellarg(serialize($params))." ".$bucketNumber." ".escapeshellarg($file_path)." > /dev/null &";
                $shellObj->execute_command($cmd);
                $bucketNumber++;
            }
            $chunk_count = BUCKET_SIZE;
            $file_path = $this->file_dir.$bucketNumber.".csv";
            $data_count = $this->putQuerydataInFile($query, $params, $bucketNumber,$file_path);
            
            if($data_count<$chunk_count){
                break;
            }
            if(file_exists(ROOT_DIR.'logs/testing.txt')){
                echo "interrupt1";die;
            }
            $bucketNumber++;
        }
        while(1){
            if(file_exists(ROOT_DIR.'logs/testing.txt')){
                echo "interrupt2";die;
            }
            $fp = file($this->file_dir.'file_log.log');
            $count = sizeof($fp);
            if($count>=$bucketNumber+1)
                break;
            else{
                $wait_time = ($bucketNumber - $count)*2;
                sleep($wait_time);
            }

        }
        for($i=0;$i<=$bucketNumber;$i++){
            $files[] = $this->file_dir.$i.".csv";
        }
        $joinFile_path = $this->file_dir.'queryData.csv';
        foreach($files as $file) {
        	$fileObj = new Helper\File($file,'r');
        	$fileObj->joinCsv($joinFile_path);
        	$fileObj->close();
        }
        $data = array("status" => 1, "message" => "success", "url" => $this->file_dir_path."queryData.csv");
        //echo "<a href='".$this->file_dir_path."queryData.csv' >Download Dump</a>";
        return $data;
	}

    public function putQuerydataInFile($query,$params,$bucketNumber,$file){
        $offset = $bucketNumber * BUCKET_SIZE;
        $query = $query . " limit " . $offset . ", " . BUCKET_SIZE;
        $excelArray = $this->getData($query,$params);
        $filePointer = new Helper\File($file,'w');
        $header = FALSE;
        foreach ($excelArray as $index=>$data){
            if(!$header && $bucketNumber==0){
           		$filePointer->putCsvData(array_keys($data));
                $header=TRUE;
            }
            $filePointer->putCsvData($excelArray[$index]);
          
        }
        $dir = dirname($file);
        $filename = explode('.',basename($file));
        $fp = new Helper\File($dir.'/file_log.log', 'a');
        $fp->filePuts($filename[0]."\n");
        $fp->close();
        $filePointer->close();
        return count($excelArray);
    }
}