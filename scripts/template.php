<?php
require_once 'dataclass.php';
require_once 'database.php';
require_once 'templateview.php';

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
		return new $templateclass($templatedata);
	}
	
	function getTemplateView($view) {
		return TemplateView::getTemplateView($this->id, $view);
	}
	
	static function getMimeType() {
		if (isset($_GET['mime'])) {
			$mimetype = $_GET['mime'];
		} else {
			$mimetype = $_SERVER['HTTP_ACCEPT'];
		}
		
		// TODO: select the MIME type of choice
		
		if (isset($_GET['mime'])) return $_GET['mime'];
		return 'application/x-fuji+xhtml+xml';
		
		// TODO: else 406 Not Acceptable
		// Note: Quote: HTTP/1.1 servers are allowed to return responses which are
		// not acceptable according to the accept headers sent in the request. (rfc2616 10.4.7)
	}
	
}

?>