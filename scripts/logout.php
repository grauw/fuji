<?php
require_once 'usermanager.php';
require_once 'template.php';
require_once 'resource.php';

class Logout extends Resource {
	
	function httpGet()
	{
		UserManager::authenticate();
		if (UserManager::isUser())
			UserManager::forceAuthenticate();
		$template = Template::getTemplate();
		$template->applyView('logout');
	}
	
}

new Logout();
	
?>