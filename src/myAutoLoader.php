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
		if(file_exists(MODEL_DIR . strtolower($classname).'.php'))
			include MODEL_DIR . strtolower($classname) . '.php';
		else if(file_exists(CONNECTION_DIR . strtolower($classname).'.php'))
			include CONNECTION_DIR . strtolower($classname) . '.php';
		else if(file_exists(HELPERS_DIR . strtolower($classname).'.php'))
			include HELPERS_DIR . strtolower($classname) . '.php';
	}
}