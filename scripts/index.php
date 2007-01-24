<?php
require_once 'entry.php';
require_once 'usermanager.php';
require_once 'template.php';
require_once 'resource.php';
require_once 'pluginmanager.php';

class Index extends Resource {
	
	function httpGet()
	{
		if (!file_exists("configuration/installer_disabled")) {
			header('Location: ' . ($_SERVER['HTTPS']?'https://':'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . 'install');
			header('HTTP/1.1 303 See Other');
			exit;
		}
		UserManager::authenticate();
		$template = Template::getTemplate();
		$template->applyView('index');
	}
	
}
	
new Index();
	
?>