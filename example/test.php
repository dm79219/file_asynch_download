<?php 

require_once __DIR__ . '/vendor/autoload.php'; // Autoload files using Composer autoload

$obj = new Library();
$sql = "select * from <table_name>"; // Query here
$response = $obj->run($sql);
print_r($response);
