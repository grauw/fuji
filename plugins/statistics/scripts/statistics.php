<?php
require_once '../../../scripts/entry.php';
require_once '../../../scripts/usermanager.php';
require_once '../../../scripts/template.php';
require_once '../../../scripts/resource.php';

class Statistics extends Resource {
	
	function httpGet()
	{
		$template = Template::getPluginTemplate('statistics');
		$template->applyView('statistics');
	}
	
}
	
new Statistics();
	
?>