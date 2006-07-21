<?php
/*require_once 'entry.php';*/
require_once 'usermanager.php';
require_once 'template.php';
require_once 'resource.php';

class Menu extends Resource {
	
	function httpGet()
	{
		UserManager::authenticate();
		$action = isset($_GET['action']) ? $_GET['action'] : 0;
		$template = Template::getTemplate();
		$template->applyView('menu');
	}
	
}	
	
new Menu();
	
?>