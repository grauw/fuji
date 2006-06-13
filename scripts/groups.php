<?php
require_once 'usermanager.php';
require_once 'template.php';

new Item();
	
class Item {
	
	function __construct()
	{
		$method = $_SERVER['REQUEST_METHOD'];
		if ($method == 'GET') {
			$this->httpGet();
		} else {
			$this->httpMethodNotAllowed();
		}
	}
	
	function httpGet()
	{
		UserManager::authenticate();
		if (UserManager::isAdmin()) {
			$template = Template::getTemplate(2);
			$template->applyGroups($template);
		}
		else {			
			$lang = UserManager::getUser()->language;
			$template = Template::getTemplate(2);
			$template->failInsufficientRights($template);
		}
	}
	
	function httpMethodNotAllowed()
	{
		// 405 Method Not Allowed
		header('Allow: GET, POST, DELETE, PUT', true, '405');
	}
	
}	
	
?>