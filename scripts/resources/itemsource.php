<?php
require_once 'scripts/dataclasses/entry.php';
require_once 'scripts/usermanager.php';
require_once 'scripts/resource.php';

class ItemSource extends Resource {
	
	function httpGet()
	{
		UserManager::authenticate();
		if ($entry = $this->getEntry()) {
			if ($entry->getRights()->may_read) {
				$this->template->entry = $entry;
				$this->template->applyView('item_source');
			} else header('HTTP/1.1 401 Unauthorized');
		} else header('HTTP/1.1 404 Not Found');
	}
	
	function getEntry() {
		$uriparts = $this->request->getRequestURIParts();
		return Entry::getEntry(intval($uriparts[1]));
	}
	
}

?>
