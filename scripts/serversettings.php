<?php
require_once 'usermanager.php';
require_once 'database.php';
require_once 'resource.php';

class ServerSettings extends Resource {
	
	function httpPut()
	{
		UserManager::authenticate();
		$user = UserManager::getUser();
		if($user->isAdmin())
		{
			$default_lang = Database::getArray("SELECT value FROM defaults WHERE name = 'language'");
			$default_lang = $default_lang[0][0];
			$default_style = Database::getArray("SELECT value FROM defaults WHERE name = 'style'");
			$default_style = $default_style[0][0];
			$gzip = Database::getArray("SELECT value FROM defaults WHERE name = 'gzip'");
			$gzip = $gzip[0][0];
			$new_default_lang = $_POST['default_language'];
			$new_default_style = $_POST['default_style'];
			$new_gzip = $_POST['gzip'];
			if($new_default_lang != $default_lang)
				Database::execute("UPDATE " . Database::$db_tprefix . "defaults SET value='$new_default_lang' WHERE name='language'");
			if($new_default_style != $default_style)
				Database::execute("UPDATE " . Database::$db_tprefix . "defaults SET value='$new_default_style' WHERE name='style'");
			if($new_gzip != $gzip)
				Database::execute("UPDATE " . Database::$db_tprefix . "defaults SET value='$new_gzip' WHERE name='gzip'");					
		}
	}
	
}

new ServerSettings();

?>