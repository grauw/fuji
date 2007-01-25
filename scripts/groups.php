<?php
require_once 'usermanager.php';
require_once 'template.php';
require_once 'group.php';
require_once 'utils/functions.php';
require_once 'resource.php';

class Groups extends Resource {
	
	function httpGet()
	{
		UserManager::authenticate();
		$entry = Entry::getRoot();
		if (UserManager::getUser()->getRights($entry)->may_read) {
			$template = Template::getTemplate();
			if(isset($_GET['userid'])) $template->userid = $_GET['userid'];
			if(isset($_GET['groupid'])) $template->userid = $_GET['groupid'];
			$template->applyView('groups');
		}
		else header('HTTP/1.1 401 Unauthorized');
	}
	
	function httpPost()
	{
		UserManager::authenticate();
		if (strlen($_POST['name']) > 1 && preg_match('/[a-zA-Z0-9_-]$/', $_POST['name'])) {
			if (!Group::isGroupName($_POST['name'])) {
				if (UserManager::isAdmin()) {
					$newgroupdata = array ( 
							'name' => $_POST['name'],
							);
					$newgroup = Group::addUser($newuserdata);
					header('Location: '.$newgroup->getAbsoluteURI());
					header('HTTP/1.1 201 Created');
				} else header('HTTP/1.1 401 Unauthorized');
			}
		} else header('HTTP/1.1 404 Not Found'); //incorrect error
	}
	
	function httpDelete()
	{
		UserManager::authenticate();
		if($_GET['id']){
			Group::getGroup($_GET['id'])->delete();
		}
	}
	
}	
	
new Groups();
	
?>