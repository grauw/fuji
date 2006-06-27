<?php
/*require_once 'entry.php';*/
require_once 'usermanager.php';
require_once 'template.php';
require_once 'httprequest.php';

class Menu extends HttpRequest {
	
	function httpGet()
	{
		UserManager::authenticate();
		$action = isset($_GET['action']) ? $_GET['action'] : 0;
		$template = Template::getTemplate('AJAX');
		$template->applyMenu($template);
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
	
new Menu();
	
?>