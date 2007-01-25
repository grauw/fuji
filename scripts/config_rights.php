<?php

require_once 'entry.php';
require_once 'group.php';
require_once 'usermanager.php';
require_once 'template.php';
require_once 'resource.php';

class ItemRights extends Resource {
	
	function httpGet()
	{
		UserManager::authenticate();
		if(UserManager::isAdmin()) {
			if(isset($_GET['id'])) {
				$id = intval($_GET['id']);
				if (Entry::isEntryId($_GET['id'])) {
					$template = Template::getTemplate();
					$template->entryid = $_GET['id'];
					$template->applyView('rights');
				} else header('HTTP/1.1 404 Not Found');
			} else {
				if(isset($_GET['groupid'])) {
					if (Group::isGroupId($_GET['groupid'])) {
						$template = Template::getTemplate();
						$template->groupid = $_GET['groupid'];
						$template->applyView('rights');
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

new ItemRights();


	
?>