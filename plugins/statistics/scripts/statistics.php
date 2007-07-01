<?php
require_once 'scripts/dataclasses/entry.php';
require_once 'scripts/usermanager.php';
require_once 'scripts/dataclasses/template.php';
require_once 'scripts/resource.php';

class Statistics extends Resource {
	
	function httpGet()
	{
		$template = Template::getPluginTemplate('statistics');
		$template->applyView('statistics');
	}
	
}
	
new Statistics();
	
?>