<?php
require_once 'entry.php';
require_once 'usermanager.php';
require_once 'template.php';

new Item();
	
class Item {
	
	function __construct()
	{
		$method = $_SERVER['REQUEST_METHOD'];
		if ($method == 'GET') {
			$this->httpGet();
		} elseif ($method == 'POST') {
			$this->httpPost();
		} elseif ($method == 'DELETE') {
			$this->httpDelete();
		} elseif ($method == 'PUT') {
			$this->httpPut();
		} else {
			$this->httpMethodNotAllowed();
		}
	}
	
	function httpGet()
	{
		UserManager::authenticate();
		$id = isset($_GET['id']) ? intval($_GET['id']) : 1;
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$template = isset($_GET['template']) ? addslashes($_GET['template']) : 'AJAX';
		$entry = Entry::getEntry($id);
		if($entry)
		{
			if (UserManager::getUser()->getRights($entry)->may_read) {
				$template = Template::getTemplate($template);
				$template->apply($entry, $page, $template);
			}
			else {			
				$template = Template::getTemplate($template);
				$template->failInsufficientRights($template);
			}
		}
		else
		header('HTTP/1.0 404 Not Found');			
	}
	
	function httpPost()
	{
		UserManager::authenticate();
		if(isset($_POST['entryid']))
		{
			$entry = Entry::getEntry($_POST['entryid']);
			if($entry && UserManager::getUser()->getRights($entry)->may_edit)
			{
				if($_POST['content'] != $entry->getProcessedContent()) $entry->updateContent($_POST['content']);
				if($_POST['title'] != $entry->title) $entry->title = $_POST['title'];
			}
		}
		else
		{
			$data['ref'] = $_POST['ref'];
			$data['title'] = $_POST['title'];
			$data['userref'] = UserManager::getUser()->id;
			$data['type'] = $_POST['type'];
			$data['content'] = $_POST['content'];
			
			if(UserManager::getUser()->getRights(Entry::getEntry($data['ref']))->may_post);
				$entry = Entry::addEntry($data);
		}
	}
	
	function httpDelete()
	{	
		UserManager::authenticate();
		$id = intval($_GET['id']);			
		if(UserManager::getUser()->getRights(Entry::getEntry($id))->may_delete)
		{
			$entry = Entry::getEntry($id);
			if($entry)	$entry->remove();
			else		header('HTTP/1.0 404 Not Found');
		}
	}
	
	function httpPut()
	{
		UserManager::authenticate();		
		$id = intval($_GET['id']);
		$entry = Entry::getEntry($id);
		if($entry && UserManager::getUser()->getRights(Entry::getEntry($_POST['entryid']))->may_edit)
		{
			$entry->updateContent($_POST['content']);
			$entry->title = $_POST['title'];
		}
	}
	
	function httpMethodNotAllowed()
	{
		// 405 Method Not Allowed
		header('Allow: GET, POST, DELETE, PUT', true, '405');
	}
	
}	
	
?>