<?php
require_once 'entry.php';
require_once 'usermanager.php';
require_once 'template.php';
require_once 'httprequest.php';

class Statistics extends HttpRequest {
	
	function httpGet()
	{
		$template = Template::getTemplate('AJAX');
		$template->applyStatistics($template);
	}
	
}
	
new Statistics();
	
?>