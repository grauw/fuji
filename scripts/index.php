<?php
require_once 'entry.php';
require_once 'usermanager.php';
require_once 'template.php';
require_once 'resource.php';

class Statistics extends Resource {
	
	function httpGet()
	{	//TODO: Make sure this works too when there's no filled database, link to installer
		$template = Template::getTemplate();
		$template->applyView('index');
	}
	
}
	
new Statistics();
	
?>