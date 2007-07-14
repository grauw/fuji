<?php
require_once 'scripts/usermanager.php';
require_once 'scripts/dataclasses/template.php';
require_once 'scripts/resource.php';

class Login extends Resource {
	
	function httpGet()
	{
		$this->httpPost();
	}
	
	function httpPost()
	{
		UserManager::authenticate(true);
		$this->template->applyView('login');
	}
	
}

?>