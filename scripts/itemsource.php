<?php
require_once 'entry.php';
require_once 'usermanager.php';
require_once 'resource.php';

class ItemSource extends Resource {
	
	function httpGet()
	{
		UserManager::authenticate();
		if ($entry = $this->getEntry()) {
			if (UserManager::getUser()->getRights($entry)->may_read) {
				$template = Template::getTemplate();
				$template->entry = $entry;
				$template->applyView('item_source');
			} else header('HTTP/1.1 401 Unauthorized');
		} else header('HTTP/1.1 404 Not Found');
	}
	
	function getEntry() {
		if (!isset($_GET['id'])) return null;
		return Entry::getEntry(intval($_GET['id']));
	}
	
}

new ItemSource();
	
?>