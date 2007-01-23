<?php
require_once 'entry.php';
require_once 'usermanager.php';
require_once 'template.php';
require_once 'resource.php';

class Users extends Resource {
	
	function httpGet()
	{
		UserManager::authenticate();
		$entry = Entry::getRoot();
		if (UserManager::getUser()->getRights($entry)->may_read) {
			$template = Template::getTemplate();
			if(isset($_GET['groupid'])) $template->groupid = $_GET['groupid'];
			$template->applyView('users');
		}
		else header('HTTP/1.1 401 Unauthorized');
	}
	
	function httpDelete()
	{
		UserManager::authenticate();
		if ($user = User::getUser($_GET['id'])) {
			if (UserManager::isAdmin()) {
				$user->remove();
				header('HTTP/1.1 204 No Content');
			} else header('HTTP/1.1 401 Unauthorized');
		} else header('HTTP/1.1 404 Not Found');
	}
}
	
new Users();
	
?>