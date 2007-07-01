<?php
require_once 'scripts/dataclasses/entry.php';
require_once 'scripts/dataclasses/group.php';
require_once 'scripts/usermanager.php';
require_once 'scripts/dataclasses/template.php';
require_once 'scripts/resource.php';

class ConfigRights extends Resource {
	
	function httpGet()
	{
		UserManager::authenticate();
		if (UserManager::isAdmin()) {
			$uriparts = $this->request->getRequestURIParts();
			if ($uriparts[0] == 'users') {
				$id = intval($uriparts[1]);
            	if ($entry = Entry::getEntry($uriparts[1])) {
					$template = Template::getTemplate();
					$template->entry = $entry;
					$template->applyView('rights');
					header('HTTP/1.1 200 OK');
				} else header('HTTP/1.1 404 Not Found');
			} else {
				if ($uriparts[0] == 'groups') {
					if (Group::isGroupId($uriparts[1])) {
						$template = Template::getTemplate();
						$template->groupid = $uriparts[1];
						$template->applyView('rights');
        				header('HTTP/1.1 200 OK');
					}
				} else header('HTTP/1.1 404 Not Found');
			}
		} else header('HTTP/1.1 401 Unauthorized');
	}
	
	function httpPost()
	{
		$array = array();
		$groups = Group::getAllGroups();
		foreach($groups as $group){
			$groupid = $group->id;
			$array = array();
			if($_POST['configRightsRead' . $groupid]) $array['may_read'] = 1; else $array['may_read'] = 0;
			if($_POST['configRightsPost' . $groupid]) $array['may_post'] = 1; else $array['may_post'] = 0;
			if($_POST['configRightsEdit' . $groupid]) $array['may_edit'] = 1; else $array['may_edit'] = 0;
			if($_POST['configRightsDelete' . $groupid]) $array['may_delete'] = 1; else $array['may_delete'] = 0;
			$array['entryid'] = $id;
			$group->updateRights($array);
		}
	}
	
}

?>
