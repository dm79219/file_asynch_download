<?php
/**
* 
*/

//namespace dm79219\asynchlib\helpers;

class ShellCommand
{
	
	function __construct()
	{
		
	}

	function execute_command($cmd)
	{
		shell_exec($cmd);
	}

	function make_dir($path)
	{
		$cmd = 'mkdir ' . $path;
		$this->execute_command($cmd);
	}

	function remove_dir($path)
	{
		$cmd = 'rm -rf ' . $path;
		$this->execute_command($cmd);
	}

	function create_file($path)
	{
		$cmd = 'touch ' . $path;
		$this->execute_command($cmd);
	}

	function run_php_script($path,$params)
	{
		$pathParams = '';
		foreach ($params as $value) {
			$pathParams .= " " . $value;
		}
		$path = $path . $pathParams;
		$cmd = "php ".$path."  > ".LOG_FILE." &";
		$this->execute_command($cmd);
	}
}