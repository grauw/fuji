<?php
require_once 'scripts/dataclasses/entry.php';
require_once 'scripts/usermanager.php';
require_once 'scripts/dataclasses/template.php';
require_once 'scripts/resource.php';
require_once 'scripts/pluginmanager.php';

class Index extends Resource {
	
	function httpGet()
	{
		if (!file_exists('scripts/configuration/installer_disabled')) {
			header('Location: ' . HttpRequest::$request->getAbsoluteContextPath() . 'install');
			header('HTTP/1.1 303 See Other');
			exit;
		}
		UserManager::authenticate();
		$this->template->applyView('index');
	}
	
}

?>