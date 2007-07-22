<?php
require_once 'scripts/dataclasses/entry.php';
require_once 'scripts/dataclasses/user.php';
require_once 'scripts/usermanager.php';
require_once 'scripts/dataclasses/template.php';
require_once 'scripts/resource.php';

class Users extends Resource {
	
	function httpGet()
	{
		UserManager::authenticate();
		$entry = Entry::getRoot();
		if ($entry->getRights()->may_read) {
			$uriparts = $this->request->getRequestURIParts();
			$this->template->users = User::getUsers();
			$this->template->applyView('users');
		}
		else header('HTTP/1.1 401 Unauthorized');
	}
	
	function httpPost()
	{
		UserManager::authenticate();
		// TODO: The nickname restrictions are kind of arbitrarily stashed in here...
		//       Could use some review, too. Why such a limited set?
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
		$uriparts = $this->request->getRequestURIParts();
		if ($user = User::getUser($uriparts[1])) {
			if (UserManager::isAdmin()) {
				$user->remove();
				header('HTTP/1.1 204 No Content');
			} else header('HTTP/1.1 401 Unauthorized');
		} else header('HTTP/1.1 404 Not Found');
	}
}

?>
