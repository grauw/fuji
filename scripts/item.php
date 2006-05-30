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
		$id = isset($_GET['id']) ? intval($_GET['id']) : 1;
		$entry = Entry::getEntry($id);
		if (UserManager::getUser()->getRights($entry)->may_read) {
			$lang='en-GB';
			$template = Template::getTemplate(2);
			$template->apply($entry, $lang, $template);
		}
		else {
			$lang='en-GB';
			$template = Template::getTemplate(2);
			$template->failInsufficientRights($lang, $template);
		}
	}
	
	function httpPost()
	{
		if(isset($_POST['entryid']))
		{
			$entry = Entry::getEntry($_POST['entryid']);
			if($entry)
			{
				if($_POST['content'] != $entry->getContent()) $entry->updateContent($_POST['content']);
				if($_POST['content'] != $entry->title) $entry->title = $_POST['title'];
			}
		}
		else
		{
			$data['ref'] = $_POST['ref'];
			$data['title'] = $_POST['title'];
			$data['userref'] = $_POST['userref'];
			$data['type'] = $_POST['type'];
			
			$entry = Entry::addEntry($data);
			if($_POST['content']) $entry->addContent($_POST['content']);
		}
	}
	
	function httpDelete()
	{
		$id = intval($_POST['entryid']);
		$entry = Entry::getEntry($id);
		if($entry)	$entry->remove();
	}
	
	function httpPut()
	{
		$id = intval($_POST['entryid']);
		$entry = Entry::getEntry($id);
		if($entry)
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