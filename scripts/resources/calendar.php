<?php
require_once 'scripts/usermanager.php';
require_once 'scripts/dataclasses/template.php';
require_once 'scripts/resource.php';

class Calendar extends Resource {
	
	function httpGet()
	{
		UserManager::authenticate();
		$template = Template::getTemplate();
		$template->today = time();
		$uriparts = $this->request->getRequestURIParts();
		if (isset($uriparts[1]))
			$template->date = strtotime($uriparts[1].'-01');
		else
			$template->date = strtotime(date('Y-m-01', time()));
		$template->applyView('calendar');
	}
	
}

?>