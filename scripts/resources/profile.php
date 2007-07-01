<?php
require_once 'scripts/dataclasses/entry.php';
require_once 'scripts/dataclasses/group.php';
require_once 'scripts/usermanager.php';
require_once 'scripts/dataclasses/template.php';
require_once 'scripts/resource.php';

class Profile extends Resource {

	function httpGet()
	{
		// TODO: Really needs some more cleanup/fixes (group memberships administration shouldn't be here)
		
		$uriparts = $this->request->getRequestURIParts();
		$user = isset($uriparts[1]) ? User::getUser($uriparts[1]) : UserManager::getUser();
		
		if ($_POST['updated']){
			$groups = Database::getArray("SELECT name FROM " . DbVar::$db_tprefix . "groups");
			foreach($groups as $group) {
				$name = $group['name'];
				if ($_POST[$name]) Group::getGroupByName($name)->addUser($user);
				else Group::getGroupByName($name)->removeUser($user);
			}
		}
		
		$template = Template::getTemplate();
		$template->user = $user;
		$template->groups = $user->getGroups();
		$template->applyView('profile');
	}
	
}

?>