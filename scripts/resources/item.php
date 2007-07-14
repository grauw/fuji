<?php
require_once 'scripts/dataclasses/entry.php';
require_once 'scripts/usermanager.php';
require_once 'scripts/resource.php';

class Item extends Resource {
	
	function httpGet()
	{
		UserManager::authenticate();
		$uriparts = $this->request->getRequestURIParts();
		$page = isset($uriparts[2]) ? intval($uriparts[2]) : 1;
		if ($entry = $this->getEntry()) {
			if (UserManager::getUser()->getRights($entry)->may_read) {
				$this->template->entry = $entry;
				$this->template->page = $page;
				header('HTTP/1.1 200 OK');
				$this->template->applyView($entry->getView());
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
				$location = $entry->type != Entry::$TYPE_POST ? $newentry->getAbsoluteURI() : $entry->getAbsoluteURI() . '#i' . $newentry->id;
				if ($this->template->mimetype == 'text/html') {
					header('Location: '.$location);
					header('HTTP/1.1 200 OK');
					$this->template->redirecturl = $location;
					$this->template->applyView('item_posted');
				} else {
					header('Location: '.$location);
					header('HTTP/1.1 201 Created');
				}
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
		$uriparts = $this->request->getRequestURIParts();
		if (!isset($uriparts[1])) return null;
		return Entry::getEntry(intval($uriparts[1]));
	}
	
}

?>