<?php
require_once 'usermanager.php';
require_once 'template.php';
require_once 'group.php';
require_once 'utils/functions.php';
require_once 'resource.php';

class Groups extends Resource {
	
	function httpGet()
	{
		if (UserManager::isAdmin()) {
			$template = Template::getTemplate();
			$template->applyView('groups');
		}
		else header('HTTP/1.1 401 Unauthorized');
	}
	
	function httpPost()
	{
		UserManager::authenticate();
		if($_POST['groupName']){
			$data['name'] = $_POST['groupName'];
			$group = Group::addGroup($data);
			if($_POST['newGroupRead']) $array['may_read'] = 1; else $array['may_read'] = 0;
			if($_POST['newGroupPost']) $array['may_post'] = 1; else $array['may_post'] = 0;
			if($_POST['newGroupEdit']) $array['may_edit'] = 1; else $array['may_edit'] = 0;
			if($_POST['newGroupDelete']) $array['may_delete'] = 1; else $array['may_delete'] = 0;
			$group->updateRights($array);
		}
	}
	
	function httpPut()
	{
		UserManager::authenticate();
		$array = array();
		$group = Group::getGroup($_GET['id']);
		if($_POST['editGroupRead']) $array['may_read'] = 1; else $array['may_read'] = 0;
		if($_POST['editGroupPost']) $array['may_post'] = 1; else $array['may_post'] = 0;
		if($_POST['editGroupEdit']) $array['may_edit'] = 1; else $array['may_edit'] = 0;
		if($_POST['editGroupDelete']) $array['may_delete'] = 1; else $array['may_delete'] = 0;
		$group->updateRights($array);
	}
	
	function httpDelete()
	{
		UserManager::authenticate();
		if($_GET['id']){
			Group::getGroup($_GET['id'])->delete();
		}
	}
	
}	
	
new Groups();
	
?>