<?php
require_once 'usermanager.php';
require_once 'template.php';
require_once 'group.php';
require_once 'utils/functions.php';

new Groups();
	
class Groups {
	
	function __construct()
	{
		/*if ($_GET['delete']){
			$this->httpDelete();
		}*/
		
		$method = $_SERVER['REQUEST_METHOD'];
		if ($method == 'GET') {
			$this->httpGet();
		} elseif ($method == 'POST') {
			$this->httpPost();
		} elseif ($method == 'DELETE') {
			$this->httpDelete();
		} else {
			$this->httpMethodNotAllowed();
		}
	}
	
	function httpGet()
	{
		if (UserManager::isAdmin()) {
			$template = Template::getTemplate('AJAX');
			$template->applyGroups($template);
		}
		else {			
			$lang = UserManager::getUser()->language;
			$template = Template::getTemplate('AJAX');
			$template->failInsufficientRights($template);
		}
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
		elseif($_POST['editGroupId']){
			$array = array();
			$group = Group::getGroup($_POST['editGroupId']);
			if($_POST['editGroupRead']) $array['may_read'] = 1; else $array['may_read'] = 0;
			if($_POST['editGroupPost']) $array['may_post'] = 1; else $array['may_post'] = 0;
			if($_POST['editGroupEdit']) $array['may_edit'] = 1; else $array['may_edit'] = 0;
			if($_POST['editGroupDelete']) $array['may_delete'] = 1; else $array['may_delete'] = 0;
			$group->updateRights($array);
		}
		elseif($id = $_POST['configRightsEntryId']){
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
	
	function httpDelete()
	{
		UserManager::authenticate();
		if($_GET['id']){
			Group::getGroup($_GET['id'])->delete();
		}
	}
	
	function httpMethodNotAllowed()
	{
		// 405 Method Not Allowed
		header('Allow: GET, POST, DELETE', true, '405');
	}
	
}	
	
?>