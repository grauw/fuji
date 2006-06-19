<?php
require_once 'entry.php';
require_once 'usermanager.php';
require_once 'template.php';

new Userlist();
	
class Userlist {
	
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
		$entry = Entry::getRoot();
		if (UserManager::getUser()->getRights($entry)->may_read) {
			$template = Template::getTemplate('AJAX');
			if(isset($_GET['groupid'])) $template->applyUserlist($template, $_GET['groupid']);
			else $template->applyUserlist($template);
		}
		else {
			$template = Template::getTemplate('AJAX');
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