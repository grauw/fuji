<?php
require_once 'entry.php';
require_once 'usermanager.php';
require_once 'template.php';
require_once 'resource.php';
require_once 'pluginmanager.php';

class Index extends Resource {
	
	function httpGet()
	{
		UserManager::authenticate();
		$template = Template::getTemplate();
		$template->applyView('index');
	}
	
}
	
new Index();
	
?>