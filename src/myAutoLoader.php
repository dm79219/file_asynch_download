<?php

/**
* 
*/
class MyAutoLoader
{
	
	function __construct()
	{
		# code...
	}

	public static function Load($classname)
	{
		// project-specific namespace prefix
	    $prefix = 'dm79219\\asynchlib\\';

	    // base directory for the namespace prefix
	    $base_dir = __DIR__ . '/';// . '/src/';

	    // does the class use the namespace prefix?
	    $len = strlen($prefix);
	    if (strncmp($prefix, $classname, $len) !== 0) {
	        // no, move to the next registered autoloader
	        return;
	    }

	    // get the relative class name
	    $relative_class = substr($classname, $len);
	    // replace the namespace prefix with the base directory, replace namespace
	    // separators with directory separators in the relative class name, append
	    // with .php
	    $file = $base_dir . str_replace('\\', '/', strtolower($relative_class)) . '.php';
		if(file_exists($file))
			include $file;
	}
}