<?php
require_once 'usermanager.php';
require_once 'template.php';
require_once 'resource.php';

class Calendar extends Resource {
	
	function httpGet()
	{
		UserManager::authenticate();
		$template = Template::getTemplate();
		$template->today = time();
		if (isset($_GET['date']))
			$template->date = strtotime($_GET['date'].'-01');
		else
			$template->date = strtotime(date('Y-m-01', time()));
		$template->applyView('calendar');
	}
	
}	
	
new Calendar();
	
?>