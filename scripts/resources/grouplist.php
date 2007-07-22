<?php
require_once 'scripts/usermanager.php';
require_once 'scripts/dataclasses/template.php';
require_once 'scripts/dataclasses/group.php';
require_once 'scripts/dataclasses/user.php';
require_once 'scripts/dataclasses/entry.php';
require_once 'scripts/utils/functions.php';
require_once 'scripts/resource.php';

class Grouplist extends Resource {
	
	function httpGet()
	{
		UserManager::authenticate();
		$entry = Entry::getRoot();
		if ($entry->getRights()->may_read) {
			$uriparts = $this->request->getRequestURIParts();
		    $this->template->applyView('grouplist');
		}
		else header('HTTP/1.1 401 Unauthorized');
	}
	
	function getGroup() {
		$uriparts = $this->request->getRequestURIParts();
		if (!isset($uriparts[1])) return null;
		return Group::getGroup(intval($uriparts[1]));
	}
}

?>
