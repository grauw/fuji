<?php
require_once 'scripts/dataclasses/entry.php';
require_once 'scripts/dataclasses/group.php';
require_once 'scripts/usermanager.php';
require_once 'scripts/dataclasses/template.php';
require_once 'scripts/resource.php';

class Profile extends Resource {

	function httpGet()
	{	
		$uriparts = $this->request->getRequestURIParts();
		$user = isset($uriparts[1]) ? User::getUser($uriparts[1]) : UserManager::getUser();
		
		$this->template->user = $user;
		$this->template->groups = $user->getGroups();
		$this->template->applyView('profile');
	}
	
}

?>
