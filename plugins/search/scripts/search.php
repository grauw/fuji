<?php
require_once '../../../scripts/entry.php';
require_once '../../../scripts/usermanager.php';
require_once '../../../scripts/resource.php';

class Search extends Resource {
	
	function httpGet()
	{
		UserManager::authenticate();
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$field = isset($_GET['field']) ? $_GET['field'] : 'title';
		$order = isset($_GET['order']) ? $_GET['order'] . ' DESC' : 'score DESC';
		$entries = isset($_GET['query']) ? Entry::search($_GET['query'], $field, $_GET['forum'], $order) : null;
		if (UserManager::getUser()->getRights(Entry::getRoot())->may_read) {
			$templateobj = Template::getPluginTemplate('search');
			$templateobj->entries = $entries;
			$templateobj->page = $page;
			$templateobj->applyView('search');
		} else header('HTTP/1.1 401 Unauthorized');
		// TODO: Quote: The response MUST include a WWW-Authenticate header field (rfc2616 10.4.2)
	}
}

new Search();
?>