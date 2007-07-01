<?php
require_once 'scripts/usermanager.php';
require_once 'scripts/pluginmanager.php';
require_once 'scripts/resource.php';
require_once 'scripts/database.php';

class Plugins extends Resource {
	
	function httpGet()
	{
		//Scans for new plugins and adds them to the database
		UserManager::authenticate();
		if(UserManager::isAdmin())
		{
			PluginManager::reloadPlugins();
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

?>