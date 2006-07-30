<?php
require_once 'usermanager.php';
require_once 'template.php';
require_once 'resource.php';

class Calendar extends Resource {
	
	function httpGet()
	{
		UserManager::authenticate();
		$template = Template::getTemplate();
		$template->applyView('calendar');
	}
	
}	
	
new Calendar();
	
?>