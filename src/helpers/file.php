<?php
/**
* file.php
* Divyanshu Mahajan <divyanshumahajan1@gmail.com>
*  vim: expandtab sw=4 ts=4 sts=4:
*/

namespace dm79219\asynchlib\helpers;

class File
{
	private $filePointer;
	private $filename;
	private $mode;
	function __construct($filename, $mode)
	{
		$this->filename = $filename;
		$this->mode = $mode;
		$this->filePointer = fopen($filename, $mode);
	}

	function changeMode($mode){
		$this->close();
		$newObj = new File($this->filename,$mode);
		return $newObj;
	}

	function changeFile($filename){
		$this->close();
		$newObj = new File($filename,$this->mode);
		return $newObj;
	}

	function isEof()
	{
		return feof($this->filePointer);
	}

	function fileGets()
	{
		return fgets($this->filePointer);
	}

	function putCsvData($data){
		fputcsv($this->filePointer, $data);
	}

	function filePuts($data){
		fwrite($this->filePointer, $data);
	}

	function joinCsv($result){
		$resObj = new File($result,"a");
        while(!$this->isEof() &&  $data = $this->fileGets()) {
            $resObj->filePuts($data);
        }
        //$this->close();
        $resObj->filePuts("\n"); //usually last line doesn't have a newline
	    $resObj->close();
	}

	function close(){
		fclose($this->filePointer);
	}
}
