<?php
use \dm79219\asynchLib as Library;
$query = $argv[1];
$params = $argv[2]?unserialize($argv[2]):'';
$bucketNumber = $argv[3]?:0;
$file = $argv[4];

include("library.php");
$libObj = new Library\Library();
$libObj->getDataChunkWise($query,$params,$bucketNumber,$file);