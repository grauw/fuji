<?php
require_once 'entry.php';
require_once 'usermanager.php';
require_once 'resource.php';

class Item extends Resource {
	
	function httpGet()
	{
		UserManager::authenticate();
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		if ($entry = $this->getEntry()) {
			if (UserManager::getUser()->getRights($entry)->may_read) {
				$template = Template::getTemplate();
				$template->entry = $entry;
				$template->page = $page;
				$template->applyView($entry->getView());
				header('HTTP/1.1 200 OK');
			} else header('HTTP/1.1 401 Unauthorized');
		} else header('HTTP/1.1 404 Not Found');
	}
	
	function httpPost()
	{
		UserManager::authenticate();
		if ($entry = $this->getEntry()) {
			if (UserManager::getUser()->getRights($entry)->may_post) {
				$newentry = $entry->newReply();
				$newentry->title   = $_POST['title'];
				$newentry->type    = $_POST['type'];
				$newentry->processor = $_POST['processor']; //TODO: check if user is allowed to use processor
				$newentry->userref = UserManager::getUser()->id;
				$newentry->content = $_POST['content'];
				$newentry->save();
				header('Location: '.$newentry->getAbsoluteURI());
				header('HTTP/1.1 201 Created');
			} else header('HTTP/1.1 401 Unauthorized');
		} else header('HTTP/1.1 404 Not Found');
	}
	
	function httpDelete()
	{
		UserManager::authenticate();
		if ($entry = $this->getEntry()) {
			if (UserManager::getUser()->getRights($entry)->may_delete) {
				$entry->remove();
				header('HTTP/1.1 204 No Content');
			} else header('HTTP/1.1 401 Unauthorized');
		} else header('HTTP/1.1 404 Not Found');
	}
	
	function httpPut()
	{
		UserManager::authenticate();
		if ($entry = $this->getEntry()) {
			if (UserManager::getUser()->getRights($entry)->may_edit) {
				$entry->content = $_POST['content'];
				$entry->title   = $_POST['title'];
				$entry->processor = $_POST['processor']; //TODO: check if user is allowed to use processor
				$entry->save();
				header('HTTP/1.1 204 No Content');
			} else header('HTTP/1.1 401 Unauthorized');
		} else header('HTTP/1.1 404 Not Found');
	}
	
	function getEntry() {
		if (!isset($_GET['id'])) return null;
		return Entry::getEntry(intval($_GET['id']));
	}
	
}

new Item();
	
?>