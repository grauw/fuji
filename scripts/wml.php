<?php
require_once 'entry.php';
require_once 'usermanager.php';
require_once 'template.php';

new WML();
	
class WML {
	
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
		$id = isset($_GET['id']) ? intval($_GET['id']) : 1;
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$entry = Entry::getEntry($id);
		if($entry)
		{
			if (UserManager::getUser()->getRights($entry)->may_read) {
				$template = Template::getTemplate('WML');
				$template->apply($entry, $page, $template);
			}
			else {			
				$template = Template::getTemplate('WML');
				$template->failInsufficientRights($template);
			}
		}
		else
		header('HTTP/1.0 404 Not Found');			
	}
	
	function httpMethodNotAllowed()
	{
		// 405 Method Not Allowed
		header('Allow: GET, POST, DELETE, PUT', true, '405');
	}
	
}	
	
?>