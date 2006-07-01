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
		$id = isset($_GET['id']) ? intval($_GET['id']) : 1;
		$groups = Group::getAllGroups();
		$entry = Entry::getEntry($id);
		if ($entry)
		{
			$template = Template::getTemplate();
			$template->applyRights($entry, $groups);
		}
		else
		header('HTTP/1.1 404 Not Found');			
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