<?php
require_once 'usermanager.php';
require_once 'template.php';
require_once "utils/functions.php";

new Item();
	
class Item {
	
	function __construct()
	{
		$method = $_SERVER['REQUEST_METHOD'];
		if ($method == 'GET') {
			$this->httpGet();
		} elseif ($method == 'POST') {
			$this->httpPost();
		} else {
			$this->httpMethodNotAllowed();
		}
	}
	
	function httpGet()
	{
		UserManager::authenticate();
		if (UserManager::isAdmin()) {
			$template = Template::getTemplate(2);
			$template->applyGroups($template);
		}
		else {			
			$lang = UserManager::getUser()->language;
			$template = Template::getTemplate(2);
			$template->failInsufficientRights($template);
		}
	}
	
	function httpPost()
	{
		UserManager::authenticate();
		if($_POST['groupName']){
			$data['name'] = $_POST['groupName'];
			$group = Group::addGroup($data);
			if($_POST['read']) $array['may_read'] = 1; else $array['may_read'] = 0;
			if($_POST['post']) $array['may_post'] = 1; else $array['may_post'] = 0;
			if($_POST['edit']) $array['may_edit'] = 1; else $array['may_edit'] = 0;
			if($_POST['delete']) $array['may_delete'] = 1; else $array['may_delete'] = 0;
			$group->updateRights($array);
		}
		if($_POST['groupId']){
			$array = array();
			$group = Group::getGroup($_POST['groupId']);
			if($_POST['read']) $array['may_read'] = 1; else $array['may_read'] = 0;
			if($_POST['post']) $array['may_post'] = 1; else $array['may_post'] = 0;
			if($_POST['edit']) $array['may_edit'] = 1; else $array['may_edit'] = 0;
			if($_POST['delete']) $array['may_delete'] = 1; else $array['may_delete'] = 0;
			$group->updateRights($array);
		}
		
	}
	
	function httpMethodNotAllowed()
	{
		// 405 Method Not Allowed
		header('Allow: GET, POST', true, '405');
	}
	
}	
	
?>