<?php
require_once 'dataclass.php';
require_once 'database.php';
require_once 'templateview.php';
require_once 'l10nmanager.php';

class Template extends Dataclass {
	
	static function getTable() {
		return 'templates';
	}
	
	static function getTemplate($mimetype = NULL) {
		if ($mimetype == NULL) $mimetype = self::getMimeType();
		$data = Database::getArray("SELECT * FROM " . DbVar::$db_tprefix . "templates WHERE mimetype = '".addslashes($mimetype)."'");
		$templatedata = $data[0];
		if(!$templatedata) trigger_error('template not found');
		$templateclass = require_once(realpath(dirname(__FILE__).'/../'.$templatedata['basedir']));
		header("Content-Type: $mimetype; charset=UTF-8");
		return new $templateclass($templatedata);
	}
	
	function getTemplateView($view) {
		if (!preg_match('/^\w+$/', $view)) return FALSE;
		$file = dirname($this->basedir).'/'.$view.'.php';
		if (!file_exists(realpath(dirname(__FILE__).'/../'.$file))) return FALSE;
		return realpath(dirname(__FILE__).'/../'.$file);
	}
	
	function applyView($view) {
		L10nManager::loadL10n();
		require_once($this->getTemplateView($view));
	}
	
	static function getMimeType() {
		if (isset($_GET['mime'])) {
			$mimetype = $_GET['mime'];
		} else {
			$mimetype = $_SERVER['HTTP_ACCEPT'];
		}
		
		// TODO: select the MIME type of choice
		
		Resource::addVary('Content-Type');
		if (isset($_GET['mime'])) return $_GET['mime'];
		if (self::acceptsFujiXHTML()) return 'application/x-fuji+xhtml+xml';
		if (self::acceptsXHTML()) return 'application/xhtml+xml';
		return 'text/html';
		
		// TODO: else 406 Not Acceptable
		// Note: Quote: HTTP/1.1 servers are allowed to return responses which are
		// not acceptable according to the accept headers sent in the request. (rfc2616 10.4.7)
	}
	
	public function acceptsXHTML()
	{
		return strpos($_SERVER['HTTP_ACCEPT'],'application/xhtml+xml') !== FALSE ? true : false;
	}
	
	public function acceptsFujiXHTML()
	{
		return strpos($_SERVER['HTTP_ACCEPT'],'application/x-fuji+xhtml+xml') !== FALSE ? true : false;
	}
	
}

?>