<?php
require_once 'scripts/usermanager.php';
require_once 'scripts/dataclasses/template.php';
require_once 'scripts/resource.php';

class Logout extends Resource {
	
	function httpGet()
	{
		$this->httpPost();
	}
	
	function httpPost()
	{
		UserManager::authenticate();
		if (UserManager::isUser())
			UserManager::forceAuthenticate();
		$this->template->applyView('logout');
	}
	
}

?>