<?php
require_once 'scripts/usermanager.php';
require_once 'scripts/dataclasses/template.php';
require_once 'scripts/resource.php';

class Menu extends Resource {
	
	function httpGet()
	{
		UserManager::authenticate();
		$template = Template::getTemplate();
		$template->applyView('menu');
	}
	
}

?>