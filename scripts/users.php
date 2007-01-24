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
	
	function httpPost()
	{
		UserManager::authenticate();
		if (strlen($_POST['nickname']) > 1 && preg_match('/[a-zA-Z0-9_-]$/', $_POST['nickname'])) {
			if (!User::isUser($_POST['nickname'])) {
				if (UserManager::isAdmin()) {
					$newuserdata = array ( 
							'nickname' => $_POST['nickname'],
							'email'    => $_POST['email'],
							'password' => $_POST['password'],
							'picture'  => $_POST['picture']
							);
					$newuser = User::addUser($newuserdata);
					header('Location: '.$newuser->getAbsoluteURI());
					header('HTTP/1.1 201 Created');
				} else header('HTTP/1.1 401 Unauthorized');
			}
		} else header('HTTP/1.1 404 Not Found'); //incorrect error
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