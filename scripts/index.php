<?php
require_once 'entry.php';
require_once 'usermanager.php';
require_once 'template.php';
require_once 'resource.php';

class Statistics extends Resource {
	
	function httpGet()
	{
		$template = Template::getTemplate();
		$template->applyIndex();
	}
	
}
	
new Statistics();
	
?>