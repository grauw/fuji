<?php
require_once 'usermanager.php';
require_once 'resource.php';
require_once 'database.php';

class Plugins extends Resource {
	
	function httpGet()
	{
		//Scans for new plugins and adds them to the database
		UserManager::authenticate();
		if(UserManager::isAdmin())
		{
			Database::execute('DELETE FROM plugins');
			$dirs = scandir('../plugins');
			$plugins = array();
			foreach($dirs as $dir)
				if(file_exists("../plugins/$dir/$dir.php"))
					Database::execute("INSERT INTO plugins (name) VALUES ('$dir')");
		} else header('HTTP/1.1 401 Unauthorized');
	}
	function httpPut()
	{
		UserManager::authenticate();
		if(UserManager::isAdmin())
		{
			
		} else header('HTTP/1.1 401 Unauthorized');
	}	
}	

new Plugins();

?>