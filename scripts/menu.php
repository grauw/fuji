<?php
/*require_once 'entry.php';*/
require_once 'usermanager.php';
require_once 'template.php';

new Menu();
	
class Menu {
	
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
		UserManager::authenticate();
		$action = isset($_GET['action']) ? $_GET['action'] : 0;
		$lang = UserManager::getUser()->language;
		$template = Template::getTemplate(2);
		$template->applyMenu($lang, $template);
	}
	
	function httpMethodNotAllowed()
	{
		// 405 Method Not Allowed
		header('Allow: GET', true, '405');
	}
	
}	
	
?>