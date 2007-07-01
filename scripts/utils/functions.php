<?php

class Functions {
	
	static function implode_with_keys($glue, $array, $valwrap=''){
		foreach($array AS $key => $value) $ret[] = $key."=".$valwrap.$value.$valwrap;
		return implode($glue, $ret);
	}
}

?>