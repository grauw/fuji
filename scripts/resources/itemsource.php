<?php
require_once 'scripts/dataclasses/entry.php';
require_once 'scripts/usermanager.php';
require_once 'scripts/resource.php';

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
		$uriparts = $this->request->getRequestURIParts();
		return Entry::getEntry(intval($uriparts[1]));
	}
	
}

?>