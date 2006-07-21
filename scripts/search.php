<?php
require_once 'entry.php';
require_once 'usermanager.php';
require_once 'resource.php';

class Search extends Resource {
	
	function httpGet()
	{
		UserManager::authenticate();
		if(isset($_GET['query']))
		{
			$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
			$order = isset($_GET['order']) ? $_GET['order'] . ' DESC' : 'score DESC';
			$entries = Entry::search($_GET['query'], $_GET['field'], $order);
			if (UserManager::getUser()->getRights(Entry::getRoot())->may_read) {
				$templateobj = Template::getTemplate();
				$templateobj->entries = $entries;
				$templateobj->page = $page;
				$templateobj->applyView('searchresults');
			} else header('HTTP/1.1 401 Unauthorized');
		}
		else
		{
			if (UserManager::getUser()->getRights(Entry::getRoot())->may_read) {
				$templateobj = Template::getTemplate();
				$templateobj->applyView('search');
			} else header('HTTP/1.1 401 Unauthorized');			
		}
		// TODO: Quote: The response MUST include a WWW-Authenticate header field (rfc2616 10.4.2)
	}
}

new Search();
	
?>