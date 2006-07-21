<?php
require_once 'entry.php';
require_once 'usermanager.php';
require_once 'template.php';
require_once 'resource.php';

class Userlist extends Resource {
	
	function httpGet()
	{
		UserManager::authenticate();
		$entry = Entry::getRoot();
		if (UserManager::getUser()->getRights($entry)->may_read) {
			$template = Template::getTemplate();
			if(isset($_GET['groupid'])) $template->groupid = $_GET['groupid'];
			$template->applyView('userlist');
		}
		else header('HTTP/1.1 401 Unauthorized');
	}
	
}
	
new Userlist();
	
?>