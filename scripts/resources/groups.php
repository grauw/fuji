<?php
require_once 'scripts/usermanager.php';
require_once 'scripts/dataclasses/template.php';
require_once 'scripts/dataclasses/group.php';
require_once 'scripts/dataclasses/user.php';
require_once 'scripts/dataclasses/entry.php';
require_once 'scripts/utils/functions.php';
require_once 'scripts/resource.php';

class Groups extends Resource {
	
	function httpGet()
	{
		UserManager::authenticate();
		$entry = Entry::getRoot();
		if ($entry->getRights()->may_read) {
			$uriparts = $this->request->getRequestURIParts();
			if ($uriparts[0] == 'users') $this->template->userid = $uriparts[1];
			if ($uriparts[0] == 'groups') $this->template->userid = $uriparts[1];
			$this->template->applyView('groups');
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
		$uriparts = $this->request->getRequestURIParts();
		if ($uriparts[1]) {
			Group::getGroup($uriparts[1])->delete();
		}
	}
	
}

?>
