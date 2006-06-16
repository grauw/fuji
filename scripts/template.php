<?php
require_once 'dataclass.php';
require_once 'database.php';
require_once 'templateview.php';

class Template extends Dataclass {
	
	static function getTable() {
		return 'templates';
	}
	
	static function getTemplateByID($id) {
		$data = Database::getArray("SELECT * FROM " . DbVar::$db_tprefix . "templates WHERE id = '$id'");
		$templatedata = $data[0];
		if(!$templatedata) trigger_error('template not found');
		$templateclass = require_once(realpath(dirname(__FILE__).'/../'.$templatedata['basedir']));
		return new $templateclass($templatedata);
	}
	
	static function getTemplate($name) {
		$data = Database::getArray("SELECT * FROM " . DbVar::$db_tprefix . "templates WHERE name = '$name'");
		$templatedata = $data[0];
		if(!$templatedata) trigger_error('template not found');
		$templateclass = require_once(realpath(dirname(__FILE__).'/../'.$templatedata['basedir']));
		return new $templateclass($templatedata);
	}
	
	function getTemplateView($view) {
		return TemplateView::getTemplateView($this->id, $view);
	}
	
}

?>