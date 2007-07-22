<?php
require_once 'scripts/dataclasses/entry.php';
require_once 'scripts/dataclasses/user.php';
require_once 'scripts/usermanager.php';
require_once 'scripts/dataclasses/template.php';
require_once 'scripts/resource.php';

class Userlist extends Resource {
	
	function httpGet()
	{
		UserManager::authenticate();
		$entry = Entry::getRoot();
		if ($entry->getRights()->may_read) {
			$uriparts = $this->request->getRequestURIParts();
			if ($uriparts[0] == 'groups') {
				$group = Group::getGroup($uriparts[1]);
				if ($group)
					$users = $group->getUsers();
				else
					$users = array();
			} else {
				$users = User::getUsers();
			}
			$this->template->users = $users;
			$this->template->applyView('userlist');
		}
		else header('HTTP/1.1 401 Unauthorized');
	}
}

?>
