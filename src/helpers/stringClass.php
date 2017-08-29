<?php

/**
* 
*/

//namespace dm79219\asynchLib;
class StringClass
{
	
	function __construct()
	{
		# code...
	}

	function str_replace_first($search, $replace, $subject) {
	    $pos = strpos($subject, $search);
	    if ($pos !== false) {
	        return substr_replace($subject, $replace, $pos, strlen($search));
	    }
	    return $subject;
	}

	function find_all_occurences($query,$needle){
		$lastPos = 0;
		$positions = array();

		while (($lastPos = strpos($query, $needle, $lastPos))!== false) {
		    $positions[] = $lastPos;
		    $lastPos = $lastPos + strlen($needle);
		}
		return $positions;
	}
}