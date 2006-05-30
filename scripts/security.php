<?php

class Security {
	
	static function isSafe($pass)
	{
		return in_array($pass, array('god', 'system', 'root', 'password', 'pass'));	
	}
}

?>