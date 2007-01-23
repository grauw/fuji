<?php
require_once 'usermanager.php';
require_once 'template.php';
require_once 'resource.php';

class Login extends Resource {
	
	function httpGet()
	{
		httpPost();
	}
	
	function httpPost()
	{
		UserManager::authenticate(true);
		$template = Template::getTemplate();
		$template->applyView('login');
	}
	
}	
	
new Login();
	
?>