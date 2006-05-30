<?php
require_once 'dataclass.php';
require_once 'database.php';

class TemplateView extends Dataclass {
	
	private $table = 'viewtemplates';
	
	static function getTemplateView($template, $view) {
		$data = Database::getArray("SELECT * FROM " . DbVar::$db_tprefix . "viewtemplates WHERE templateref = '$template' AND typeref = '$view'");
		$templateviewdata = $data[0];
		if($templateviewdata) return new Template($templateviewdata);
		trigger_error('template view not found');
	}
	
}

?>