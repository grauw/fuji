<?php

require_once 'entry.php';
require_once 'usermanager.php';
require_once 'template.php';
require_once 'httprequest.php';

class Profile extends HttpRequest {

	function httpGet()
	{
		$template = Template::getTemplate('AJAX');
		$template->applyProfile($template);
	}
	
	function httpPost() {
		$this->httpMethodNotAllowed();
	}
	
	function httpDelete() {
		$this->httpMethodNotAllowed();
	}
	
	function httpPut() {
		$this->httpMethodNotAllowed();
	}
	
	function httpMethodNotAllowed()
	{
		// 405 Method Not Allowed
		header('Allow: GET', true, '405');
	}
}
	
new Profile();

?>