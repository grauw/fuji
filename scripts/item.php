<?php
require_once 'entry.php';
require_once 'usermanager.php';
require_once 'resource.php';

class Item extends Resource {
	
	function httpGet()
	{
		UserManager::authenticate();
		$id = isset($_GET['id']) ? intval($_GET['id']) : 1;
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$entry = Entry::getEntry($id);
		if ($entry) {
			if (UserManager::getUser()->getRights($entry)->may_read) {
				$templateobj = Template::getTemplate();
				$templateobj->apply($entry, $page);
			} else header('HTTP/1.1 401 Unauthorized');
			// TODO: Quote: The response MUST include a WWW-Authenticate header field (rfc2616 10.4.2)
		} else header('HTTP/1.1 404 Not Found');
	}
	
	function httpPost()
	{
		// Workaround: PUT does not work yet, so use this POST wizardry
		if(isset($_POST['PUT'])) return $this->httpPut();

		UserManager::authenticate();
		$data['ref'] = $_GET['id'];
		$data['title'] = $_POST['title'];
		$data['userref'] = UserManager::getUser()->id;
		$data['type'] = $_POST['type'];
		$data['content'] = $_POST['content'];
		
		if (UserManager::getUser()->getRights(Entry::getEntry($data['ref']))->may_post) {
			$entry = Entry::addEntry($data);
			header('Location: '.$_SERVER['HTTP_HOST'].$entry->getURI());
			header('HTTP/1.1 201 Created');
		} else header('HTTP/1.1 401 Unauthorized');
	}
	
	function httpDelete()
	{	
		UserManager::authenticate();
		$id = intval($_GET['id']);
		$entry = Entry::getEntry($id);
		if ($entry) {
			if (UserManager::getUser()->getRights($entry)->may_delete) {
				$entry->remove();
				header('HTTP/1.1 204 No Content');
			} else header('HTTP/1.1 401 Unauthorized');
		} else header('HTTP/1.1 404 Not Found');
	}
	
	function httpPut()
	{
		UserManager::authenticate();
		$id = intval($_GET['id']);
		$entry = Entry::getEntry($id);
		if ($entry) {
			if (UserManager::getUser()->getRights($entry)->may_edit) {
				if($_POST['content'] != $entry->getContent()) $entry->updateContent($_POST['content']);
				if($_POST['title'] != $entry->title) $entry->title = $_POST['title'];
			} else header('HTTP/1.1 401 Unauthorized');
		} else header('HTTP/1.1 404 Not Found');
	}
	
}

new Item();
	
?>