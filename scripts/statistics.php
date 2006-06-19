<?php
require_once 'entry.php';
require_once 'usermanager.php';
require_once 'template.php';

new Statistics();
	
class Statistics {
	
	function __construct()
	{
		$method = $_SERVER['REQUEST_METHOD'];
		if ($method == 'GET') {
			$this->httpGet();
		} else {
			$this->httpMethodNotAllowed();
		}
	}
	
	function httpGet()
	{
		$template = Template::getTemplate('AJAX');
		$template->applyStatistics($template);
	}
	
	function httpMethodNotAllowed()
	{
		// 405 Method Not Allowed
		header('Allow: GET, POST, DELETE, PUT', true, '405');
	}
	
}
	
?>