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
		} elseif ($method == 'POST') {
			$this->httpPost();
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
	
	function httpPost()
	{
		UserManager::authenticate();
		$data['name'] = $_POST['groupName'];
		$group = Group::addGroup($data);
		
	}
	
	function httpMethodNotAllowed()
	{
		// 405 Method Not Allowed
		header('Allow: GET, POST', true, '405');
	}
	
}	
	
?>