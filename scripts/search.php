<?php
require_once 'entry.php';
require_once 'usermanager.php';
require_once 'resource.php';

class Search extends Resource {
	
	function httpGet()
	{
		UserManager::authenticate();
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$order = isset($_GET['order']) ? $_GET['order'] : 'score DESC';
		$entries = Entry::search($_GET['query'], $_GET['field'], $order, $page);
		if (UserManager::getUser()->getRights(Entry::getRoot())->may_read) {
			$templateobj = Template::getTemplate();
			$templateobj->applySearch($entries, $page);
		} else header('HTTP/1.1 401 Unauthorized');
		// TODO: Quote: The response MUST include a WWW-Authenticate header field (rfc2616 10.4.2)
	}
}

new Search();
	
?>